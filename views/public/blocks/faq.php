<?php
/**
 * FAQ Block Template
 * @var array $block
 * @var array $blockContent
 */
$faqs = $blockContent['faqs'] ?? [];
?>
<section class="py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-2 text-gray-900">
            <?= htmlspecialchars($blockContent['title'] ?? 'Frequently Asked Questions') ?>
        </h2>
        <p class="text-gray-600 text-center mb-12">
            <?= htmlspecialchars($blockContent['subtitle'] ?? 'Got questions? We have answers.') ?>
        </p>
        
        <div class="space-y-4">
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button 
                        onclick="toggleFaq(<?= $index ?>)"
                        class="w-full px-6 py-4 text-left flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors"
                    >
                        <span class="font-medium text-gray-900"><?= htmlspecialchars($faq['question'] ?? '') ?></span>
                        <svg id="faq-icon-<?= $index ?>" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="faq-answer-<?= $index ?>" class="hidden px-6 py-4 bg-white">
                        <p class="text-gray-600"><?= nl2br(htmlspecialchars($faq['answer'] ?? '')) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if (empty($faqs)): ?>
                <!-- Default FAQs -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button onclick="toggleFaq(0)" class="w-full px-6 py-4 text-left flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors">
                        <span class="font-medium text-gray-900">Is Poker legal in India?</span>
                        <svg id="faq-icon-0" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="faq-answer-0" class="hidden px-6 py-4 bg-white">
                        <p class="text-gray-600">Skill-based games like Poker are legal in most Indian states. We operate only in jurisdictions where poker is permitted and partner with licensed clubs.</p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button onclick="toggleFaq(1)" class="w-full px-6 py-4 text-left flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors">
                        <span class="font-medium text-gray-900">How do I join a poker community?</span>
                        <svg id="faq-icon-1" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="faq-answer-1" class="hidden px-6 py-4 bg-white">
                        <p class="text-gray-600">Sign up with your phone number and select your state. We'll send you a WhatsApp invite link to join your local poker community within 24 hours.</p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button onclick="toggleFaq(2)" class="w-full px-6 py-4 text-left flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors">
                        <span class="font-medium text-gray-900">Are there any fees?</span>
                        <svg id="faq-icon-2" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="faq-answer-2" class="hidden px-6 py-4 bg-white">
                        <p class="text-gray-600">Joining our WhatsApp communities and receiving tournament updates is completely free. You only pay when you participate in paid tournaments at partner venues.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
function toggleFaq(index) {
    const answer = document.getElementById('faq-answer-' + index);
    const icon = document.getElementById('faq-icon-' + index);
    
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        answer.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}
</script>
