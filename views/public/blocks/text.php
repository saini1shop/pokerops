<?php
/**
 * Text Block Template
 * @var array $block
 * @var array $blockContent
 */
$alignment = $blockContent['alignment'] ?? 'left';
$bgColor = $blockContent['background_color'] ?? 'white';

$bgClass = match($bgColor) {
    'gray' => 'bg-gray-50',
    'dark' => 'bg-gray-900 text-white',
    'brand' => 'bg-brand-50',
    default => 'bg-white',
};

$textClass = $bgColor === 'dark' ? 'text-white' : 'text-gray-900';

$alignClass = match($alignment) {
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};
?>
<section class="py-12 px-4 <?= $bgClass ?>">
    <div class="max-w-3xl mx-auto <?= $alignClass ?>">
        <?php if (!empty($blockContent['title'])): ?>
            <h2 class="text-2xl font-bold mb-4 <?= $textClass ?>">
                <?= htmlspecialchars($blockContent['title']) ?>
            </h2>
        <?php endif; ?>
        
        <?php if (!empty($blockContent['content'])): ?>
            <div class="prose <?= $bgColor === 'dark' ? 'prose-invert' : '' ?> max-w-none">
                <?= nl2br(htmlspecialchars($blockContent['content'])) ?>
            </div>
        <?php endif; ?>
    </div>
</section>
