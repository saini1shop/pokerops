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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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

<!-- Block Selector Modal -->
<div id="block-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Add Block</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="space-y-3">
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-50 border border-gray-200 flex items-center" data-type="hero">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded flex items-center justify-center">
                        <span class="text-white font-bold text-sm">🎯</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Hero Section</div>
                    <div class="text-sm text-gray-600">Eye-catching headline with call-to-action</div>
                </div>
            </button>
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-50 border border-gray-200 flex items-center" data-type="form">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-green-100 rounded flex items-center justify-center">
                        <span class="text-green-600 font-bold text-sm">📝</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Contact Form</div>
                    <div class="text-sm text-gray-600">Lead capture form with WhatsApp consent</div>
                </div>
            </button>
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-50 border border-gray-200 flex items-center" data-type="offers">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-yellow-100 rounded flex items-center justify-center">
                        <span class="text-yellow-600 font-bold text-sm">🎁</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Offers/Benefits</div>
                    <div class="text-sm text-gray-600">Highlight features and benefits</div>
                </div>
            </button>
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-50 border border-gray-200 flex items-center" data-type="faq">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-purple-100 rounded flex items-center justify-center">
                        <span class="text-purple-600 font-bold text-sm">❓</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-gray-900">FAQ Section</div>
                    <div class="text-sm text-gray-600">Frequently asked questions with answers</div>
                </div>
            </button>
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-50 border border-gray-200 flex items-center" data-type="text">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                        <span class="text-gray-600 font-bold text-sm">📄</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Text Block</div>
                    <div class="text-sm text-gray-600">Simple text content with styling options</div>
                </div>
            </button>
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
document.getElementById('add-block-btn')?.addEventListener('click', function() {
    document.getElementById('block-modal').classList.remove('hidden');
});

document.querySelectorAll('.close-modal').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('block-modal').classList.add('hidden');
    });
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
