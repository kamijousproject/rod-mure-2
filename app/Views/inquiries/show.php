<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">แดชบอร์ด</a></li>
            <li class="breadcrumb-item"><a href="/inquiries">ข้อความ</a></li>
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
                            <img src="<?= upload_url('cars/' . $inquiry['car_image']) ?>" 
                                 class="rounded me-3" style="width: 100px; height: 75px; object-fit: cover;">
                        <?php endif; ?>
                        <div>
                            <h5 class="mb-1">
                                <a href="/cars/<?= e($inquiry['car_slug']) ?>" target="_blank">
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
            <?php if (!empty($inquiry['messages'])): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">การสนทนา</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($inquiry['messages'] as $message): ?>
                        <div class="d-flex mb-3 <?= $message['sender_id'] == $auth['id'] ? 'justify-content-end' : '' ?>">
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
                </div>
            </div>
            <?php endif; ?>
            
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
document.getElementById('reply-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const message = form.message.value.trim();
    
    if (!message) return;
    
    const response = await fetchWithCsrf('/inquiries/<?= $inquiry['id'] ?>/reply', {
        method: 'POST',
        body: JSON.stringify({ message: message })
    });
    
    const data = await response.json();
    
    if (data.success) {
        // Reload to show new message
        location.reload();
    } else {
        alert(data.message || 'เกิดข้อผิดพลาด');
    }
});
</script>
