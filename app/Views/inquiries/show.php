<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('/dashboard') ?>">แดชบอร์ด</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/inquiries') ?>">ข้อความ</a></li>
            <li class="breadcrumb-item active">รายละเอียด</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Car Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <?php if ($inquiry['car_image']): ?>
                            <img src="<?= upload_url($inquiry['car_image']) ?>" 
                                 class="rounded me-3" style="width: 100px; height: 75px; object-fit: cover;">
                        <?php endif; ?>
                        <div>
                            <h5 class="mb-1">
                                <a href="<?= url('/cars/' . e($inquiry['car_slug'])) ?>" target="_blank">
                                    <?= e($inquiry['car_title']) ?>
                                </a>
                            </h5>
                            <small class="text-muted">ประกาศที่มีการสอบถาม</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Original Inquiry -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">ข้อความต้นฉบับ</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <strong><?= e($inquiry['name']) ?></strong>
                            <br>
                            <small class="text-muted">
                                <i class="bi bi-envelope me-1"></i><?= e($inquiry['email']) ?>
                                <?php if ($inquiry['phone']): ?>
                                    | <i class="bi bi-telephone me-1"></i><?= e($inquiry['phone']) ?>
                                <?php endif; ?>
                            </small>
                        </div>
                        <small class="text-muted"><?= format_date($inquiry['created_at'], 'd M Y H:i') ?></small>
                    </div>
                    <p class="mb-0"><?= nl2br(e($inquiry['message'])) ?></p>
                </div>
            </div>
            
            <!-- Conversation -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">การสนทนา</h5>
                </div>
                <div class="card-body" id="messages-container" style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($inquiry['messages'])): ?>
                        <?php foreach ($inquiry['messages'] as $message): ?>
                            <div class="d-flex mb-3 <?= $message['sender_id'] == $auth['id'] ? 'justify-content-end' : '' ?> message-item">
                                <div class="card <?= $message['sender_id'] == $auth['id'] ? 'bg-primary text-white' : 'bg-light' ?>" 
                                     style="max-width: 70%;">
                                    <div class="card-body py-2 px-3">
                                        <small class="d-block <?= $message['sender_id'] == $auth['id'] ? 'text-white-50' : 'text-muted' ?>">
                                            <?= e($message['sender_name']) ?> - <?= format_date($message['created_at'], 'd M H:i') ?>
                                        </small>
                                        <p class="mb-0"><?= nl2br(e($message['message'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0" id="no-messages">ยังไม่มีการสนทนา</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Reply Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">ตอบกลับ</h5>
                </div>
                <div class="card-body">
                    <form id="reply-form">
                        <div class="mb-3">
                            <textarea name="message" rows="3" class="form-control" 
                                      placeholder="พิมพ์ข้อความตอบกลับ..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>ส่งข้อความ
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Contact Info -->
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">ข้อมูลผู้สอบถาม</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><i class="bi bi-person me-2"></i><?= e($inquiry['name']) ?></p>
                    <p class="mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        <a href="mailto:<?= e($inquiry['email']) ?>"><?= e($inquiry['email']) ?></a>
                    </p>
                    <?php if ($inquiry['phone']): ?>
                    <p class="mb-0">
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:<?= e($inquiry['phone']) ?>"><?= e($inquiry['phone']) ?></a>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const inquiryId = <?= $inquiry['id'] ?>;
const currentUserId = <?= $auth['id'] ?>;
let lastMessageCount = <?= count($inquiry['messages'] ?? []) ?>;
let isPolling = true;

// Reply form submit
document.getElementById('reply-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const textarea = form.message;
    const message = textarea.value.trim();
    const submitBtn = form.querySelector('button[type="submit"]');
    
    if (!message) return;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass me-2"></i>กำลังส่ง...';
    
    try {
        const response = await fetchWithCsrf('<?= url('/inquiries/' . $inquiry['id'] . '/reply') ?>', {
            method: 'POST',
            body: JSON.stringify({ message: message })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Add message to UI immediately
            addMessageToUI(data.data.message, data.data.sender_name, true);
            textarea.value = '';
            lastMessageCount++;
        } else {
            alert(data.message || 'เกิดข้อผิดพลาด');
        }
    } catch (error) {
        alert('เกิดข้อผิดพลาดในการส่งข้อความ');
    }
    
    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>ส่งข้อความ';
});

// Add message to UI
function addMessageToUI(message, senderName, isMe) {
    const container = document.getElementById('messages-container');
    if (!container) return;
    
    // Remove "no messages" placeholder
    const noMessages = document.getElementById('no-messages');
    if (noMessages) noMessages.remove();
    
    const now = new Date();
    const timeStr = now.toLocaleDateString('th-TH', { day: 'numeric', month: 'short' }) + ' ' + 
                   now.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
    
    const messageHtml = `
        <div class="d-flex mb-3 ${isMe ? 'justify-content-end' : ''} message-item new-message">
            <div class="card ${isMe ? 'bg-primary text-white' : 'bg-light'}" style="max-width: 70%;">
                <div class="card-body py-2 px-3">
                    <small class="d-block ${isMe ? 'text-white-50' : 'text-muted'}">
                        ${senderName} - ${timeStr}
                    </small>
                    <p class="mb-0">${escapeHtml(message)}</p>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', messageHtml);
    container.scrollTop = container.scrollHeight;
    
    // Flash animation for new messages
    setTimeout(() => {
        document.querySelectorAll('.new-message').forEach(el => el.classList.remove('new-message'));
    }, 500);
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML.replace(/\n/g, '<br>');
}

// Poll for new messages
async function pollMessages() {
    if (!isPolling) return;
    
    try {
        const response = await fetch('<?= url('/api/inquiries/' . $inquiry['id'] . '/messages') ?>?after=' + lastMessageCount);
        const data = await response.json();
        
        if (data.success && data.data.messages.length > 0) {
            data.data.messages.forEach(msg => {
                if (msg.sender_id != currentUserId) {
                    addMessageToUI(msg.message, msg.sender_name, false);
                }
            });
            lastMessageCount += data.data.messages.length;
        }
    } catch (error) {
        console.log('Poll error:', error);
    }
}

// Start polling every 3 seconds
setInterval(pollMessages, 3000);

// Stop polling when page is hidden
document.addEventListener('visibilitychange', () => {
    isPolling = !document.hidden;
});
</script>
