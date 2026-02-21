<?php
/**
 * Hero Block Template
 * @var array $block
 * @var array $blockContent
 */
$bgImage = $blockContent['background_image'] ?? null;
$style = $bgImage ? "background-image: url('" . htmlspecialchars($bgImage) . "'); background-size: cover; background-position: center;" : '';
?>
<section class="relative min-h-[500px] flex items-center justify-center <?= $bgImage ? '' : 'bg-gradient-to-br from-brand-900 to-gray-900' ?> text-white" style="<?= $style ?>">
    <?php if ($bgImage): ?>
        <div class="absolute inset-0 bg-black/60"></div>
    <?php endif; ?>
    
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
            <?= htmlspecialchars($blockContent['headline'] ?? 'Welcome to PokerOps') ?>
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 mb-8">
            <?= htmlspecialchars($blockContent['subheadline'] ?? '') ?>
        </p>
        <?php if (!empty($blockContent['cta_text'])): ?>
            <a href="#form" class="inline-block bg-brand-600 hover:bg-brand-700 text-white font-semibold py-4 px-8 rounded-lg text-lg transition-colors">
                <?= htmlspecialchars($blockContent['cta_text']) ?>
            </a>
        <?php endif; ?>
    </div>
</section>
