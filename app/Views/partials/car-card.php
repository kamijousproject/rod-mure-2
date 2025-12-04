<?php
/**
 * Car Card Partial - Modern minimal design
 * Expected variables: $car
 */

$imageUrl = !empty($car['primary_image']) 
    ? upload_url($car['primary_image']) 
    : 'https://placehold.co/400x300/f1f5f9/94a3b8?text=No+Image';
?>

<div class="card car-card h-100">
    <div class="card-img-wrapper">
        <?php if (!empty($car['is_featured'])): ?>
            <span class="badge-featured">
                <i class="bi bi-star-fill me-1"></i>แนะนำ
            </span>
        <?php endif; ?>
        
        <a href="<?= url('/cars/' . e($car['slug'])) ?>">
            <img src="<?= e($imageUrl) ?>" 
                 class="card-img-top" 
                 alt="<?= e($car['title']) ?>"
                 loading="lazy">
        </a>
    </div>
    
    <div class="card-body">
        <p class="text-muted small mb-1" style="font-size: 0.8rem;">
            <?= e($car['brand_name'] ?? '') ?> · <?= e($car['model_name'] ?? '') ?>
        </p>
        
        <h5 class="card-title">
            <a href="<?= url('/cars/' . e($car['slug'])) ?>">
                <?= e(str_limit($car['title'], 45)) ?>
            </a>
        </h5>
        
        <div class="specs">
            <span class="spec-badge"><?= e($car['year']) ?></span>
            <span class="spec-badge"><?= format_number($car['mileage']) ?> กม.</span>
            <span class="spec-badge"><?= e(\App\Models\Car::TRANSMISSIONS[$car['transmission']] ?? $car['transmission']) ?></span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top: 1px solid var(--gray-100);">
            <span class="price"><?= format_price($car['price']) ?></span>
            <span class="text-muted" style="font-size: 0.8rem;">
                <i class="bi bi-geo-alt"></i> <?= e($car['province'] ?? '') ?>
            </span>
        </div>
    </div>
</div>
