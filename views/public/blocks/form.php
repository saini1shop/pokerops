<?php
/**
 * Form Block Template
 * @var array $block
 * @var array $blockContent
 * @var int $page['id']
 */

$fieldsConfig = json_decode($page['fields_config'], true) ?? [];
$stateOptions = $this->getStateOptions ?? []; // Would be populated from igp_states
?>
<section id="form" class="py-16 px-4 bg-white">
    <div class="max-w-xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-2 text-gray-900">
            <?= htmlspecialchars($blockContent['title'] ?? 'Join the Community') ?>
        </h2>
        <p class="text-gray-600 text-center mb-8">
            <?= htmlspecialchars($blockContent['subtitle'] ?? 'Enter your details to get started') ?>
        </p>
        
        <form data-landing-form class="space-y-4" method="POST" action="/api/landing/signup">
            <input type="hidden" name="landing_page_id" value="<?= $page['id'] ?>">
            
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                <input 
                    type="text" 
                    name="name" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    placeholder="Enter your full name"
                >
            </div>
            
            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                <input 
                    type="tel" 
                    name="phone" 
                    required
                    pattern="[0-9]{10}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    placeholder="10-digit mobile number"
                >
            </div>
            
            <!-- Email (optional) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                <input 
                    type="email" 
                    name="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    placeholder="your@email.com"
                >
            </div>
            
            <!-- State -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                <select 
                    name="state_id" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                >
                    <option value="">Select your state</option>
                    <option value="1">Andhra Pradesh</option>
                    <option value="2">Delhi</option>
                    <option value="3">Goa</option>
                    <option value="4">Gujarat</option>
                    <option value="5">Haryana</option>
                    <option value="6">Karnataka</option>
                    <option value="7">Maharashtra</option>
                    <option value="8">Punjab</option>
                    <option value="9">Rajasthan</option>
                    <option value="10">Telangana</option>
                    <option value="11">Uttar Pradesh</option>
                    <option value="12">West Bengal</option>
                </select>
            </div>
            
            <!-- WhatsApp Consent -->
            <div class="flex items-start">
                <input 
                    type="checkbox" 
                    id="whatsapp_consent" 
                    name="whatsapp_consent" 
                    value="1"
                    checked
                    required
                    class="mt-1 w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500"
                >
                <label for="whatsapp_consent" class="ml-2 text-sm text-gray-600">
                    I agree to receive WhatsApp messages about poker events and offers *
                </label>
            </div>
            
            <!-- Marketing Consent -->
            <div class="flex items-start">
                <input 
                    type="checkbox" 
                    id="marketing_consent" 
                    name="marketing_consent" 
                    value="1"
                    class="mt-1 w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500"
                >
                <label for="marketing_consent" class="ml-2 text-sm text-gray-600">
                    I also want to receive marketing emails and SMS
                </label>
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-4 rounded-lg text-lg transition-colors"
            >
                <?= htmlspecialchars($blockContent['submit_text'] ?? 'Get Started') ?>
            </button>
            
            <p class="text-xs text-gray-500 text-center">
                By submitting, you agree to our Terms and Privacy Policy
            </p>
        </form>
    </div>
</section>
