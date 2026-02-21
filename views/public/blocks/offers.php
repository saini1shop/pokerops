<?php
/**
 * Offers Block Template
 * @var array $block
 * @var array $blockContent
 */
$offers = $blockContent['offers'] ?? [];
?>
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-2 text-gray-900">
            <?= htmlspecialchars($blockContent['title'] ?? 'What You Get') ?>
        </h2>
        <p class="text-gray-600 text-center mb-12">
            <?= htmlspecialchars($blockContent['subtitle'] ?? '') ?>
        </p>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($offers as $offer): ?>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <?php if (!empty($offer['icon'])): ?>
                        <div class="w-12 h-12 bg-brand-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= htmlspecialchars($offer['icon']) ?>"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <h3 class="font-semibold text-lg mb-2"><?= htmlspecialchars($offer['title'] ?? '') ?></h3>
                    <p class="text-gray-600"><?= htmlspecialchars($offer['description'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
            
            <?php if (empty($offers)): ?>
                <!-- Default offers if none configured -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-brand-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Join Exclusive Communities</h3>
                    <p class="text-gray-600">Connect with poker players in your city via WhatsApp groups.</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-brand-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Tournament Updates</h3>
                    <p class="text-gray-600">Get notified about upcoming tournaments in your area.</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-brand-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Special Offers</h3>
                    <p class="text-gray-600">Access exclusive deals and promotions from partner clubs.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
