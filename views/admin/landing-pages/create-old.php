<?php
/**
 * Create Landing Page View
 * @var array $campaigns
 * @var array $states
 */

$title = 'Create Landing Page';
$contentView = __DIR__ . '/create-content.php';
include __DIR__ . '/../layout.php';
?>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Landing Page</h1>
            <p class="text-gray-600 mt-1">Build a new marketing page to collect leads</p>
        </div>
        <a href="/admin/landing-pages" class="text-gray-600 hover:text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Pages
        </a>
    </div>
</div>

<form action="/admin/landing-pages" method="POST" class="space-y-6">
    <!-- Basic Information -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Page Title *
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    placeholder="e.g., Poker Tournament 2025"
                >
            </div>
            
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                    URL Slug *
                </label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg">
                        pokerops.in/
                    </span>
                    <input 
                        type="text" 
                        id="slug" 
                        name="slug" 
                        required 
                        class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-none rounded-r-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="tournament-2025"
                    >
                </div>
                <p class="text-xs text-gray-500 mt-1">Lowercase letters, numbers, and hyphens only</p>
            </div>
        </div>
        
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Description
            </label>
            <textarea 
                id="description" 
                name="description" 
                rows="3" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                placeholder="Brief description of this landing page..."
            ></textarea>
        </div>
    </div>

    <!-- Targeting & Attribution -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Targeting & Attribution</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="campaign_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Campaign (Optional)
                </label>
                <select id="campaign_id" name="campaign_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">No campaign attribution</option>
                    <?php foreach ($campaigns as $campaign): ?>
                        <option value="<?= $campaign['id'] ?>">
                            <?= htmlspecialchars($campaign['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="target_state" class="block text-sm font-medium text-gray-700 mb-1">
                    Target State (Optional)
                </label>
                <select id="target_state" name="target_state" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">All states</option>
                    <?php foreach ($states as $state): ?>
                        <option value="<?= $state['id'] ?>">
                            <?= htmlspecialchars($state['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Page Content</h2>
        
        <!-- Content Builder Placeholder -->
        <div id="content-builder" class="space-y-4">
            <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Start Building Your Page</h3>
                <p class="text-gray-600 mb-4">Add blocks like hero sections, forms, offers, and more</p>
                <button type="button" id="add-block-btn" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Block
                </button>
            </div>
        </div>
        
        <!-- Hidden content field -->
        <input type="hidden" name="content" id="content-input" value="[]">
    </div>

    <!-- Settings -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Settings</h2>
        
        <div class="space-y-4">
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active" 
                    checked 
                    class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded"
                >
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Page is active (can receive traffic)
                </label>
            </div>
            
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="auto_publish" 
                    name="auto_publish" 
                    class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded"
                >
                <label for="auto_publish" class="ml-2 block text-sm text-gray-900">
                    Publish immediately after creation
                </label>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end space-x-4">
        <a href="/admin/landing-pages" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Create Page
        </button>
    </div>
</form>

<!-- Block Selector Modal (placeholder) -->
<div id="block-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add Block</h3>
            <div class="space-y-2">
                <button type="button" class="block-add w-full text-left p-3 rounded hover:bg-gray-50" data-type="hero">
                    🎯 Hero Section
                </button>
                <button type="button" class="block-add w-full text-left p-3 rounded hover:bg-gray-50" data-type="form">
                    📝 Contact Form
                </button>
                <button type="button" class="block-add w-full text-left p-3 rounded hover:bg-gray-50" data-type="offers">
                    🎁 Offers
                </button>
                <button type="button" class="block-add w-full text-left p-3 rounded hover:bg-gray-50" data-type="faq">
                    ❓ FAQ
                </button>
                <button type="button" class="block-add w-full text-left p-3 rounded hover:bg-gray-50" data-type="text">
                    📄 Text Block
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Basic slug generation
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    
    document.getElementById('slug').value = slug;
});

// Block modal
document.getElementById('add-block-btn').addEventListener('click', function() {
    document.getElementById('block-modal').classList.remove('hidden');
});

document.getElementById('block-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

// Block adding (placeholder)
document.querySelectorAll('.block-add').forEach(btn => {
    btn.addEventListener('click', function() {
        const type = this.dataset.type;
        alert(`Adding ${type} block - implementation coming soon!`);
        document.getElementById('block-modal').classList.add('hidden');
    });
});
</script>
