<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Landing Page</h1>
            <p class="text-gray-600 mt-1">Make changes to "<?= htmlspecialchars($page['title']) ?>"</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/admin/landing-pages/preview/<?= $page['id'] ?>" target="_blank"
               class="text-green-600 hover:text-green-900 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Preview
            </a>
            <a href="/admin/landing-pages" class="text-gray-600 hover:text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Pages
            </a>
        </div>
    </div>
</div>

<form action="/admin/landing-pages/<?= $page['id'] ?>" method="POST" class="space-y-6">
    <input type="hidden" name="_method" value="PUT">

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
                    value="<?= htmlspecialchars($page['title']) ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
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
                        value="<?= htmlspecialchars($page['slug']) ?>"
                        class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-none rounded-r-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    >
                </div>
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
            ><?= htmlspecialchars($page['description'] ?? '') ?></textarea>
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
                        <option value="<?= $campaign['id'] ?>" <?= ($page['campaign_id'] == $campaign['id']) ? 'selected' : '' ?>>
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
                        <option value="<?= $state['id'] ?>" <?= ($page['target_state_id'] == $state['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($state['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Page Content Builder -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Page Content</h2>
            <button type="button" id="add-block-btn" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Block
            </button>
        </div>

        <!-- Content Builder -->
        <div id="content-builder" class="space-y-4 min-h-96">
            <?php if (empty($blocks)): ?>
                <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No blocks yet</h3>
                    <p class="text-gray-600 mb-4">Add blocks like hero sections, forms, offers, and more</p>
                    <button type="button" id="add-first-block-btn" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add First Block
                    </button>
                </div>
            <?php else: ?>
                <?php foreach ($blocks as $index => $block): ?>
                    <div class="block-item bg-gray-50 border border-gray-200 rounded-lg p-4 relative" data-type="<?= $block['type'] ?>" data-index="<?= $index ?>">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <span class="block-type-badge px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    <?= ucfirst($block['type']) ?>
                                </span>
                                <span class="ml-2 text-sm text-gray-600">Block #<?= $index + 1 ?></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" class="move-block text-gray-400 hover:text-gray-600" title="Move">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                    </svg>
                                </button>
                                <button type="button" class="edit-block text-blue-600 hover:text-blue-800" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button type="button" class="delete-block text-red-600 hover:text-red-800" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Block Preview -->
                        <div class="block-preview text-sm text-gray-600">
                            <?php if ($block['type'] === 'hero'): ?>
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 rounded">
                                    <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($block['data']['headline'] ?? 'Hero Headline') ?></h2>
                                    <p class="mb-3"><?= htmlspecialchars($block['data']['subheadline'] ?? 'Hero subheadline...') ?></p>
                                    <button class="bg-white text-blue-600 px-4 py-2 rounded">CTA Button</button>
                                </div>
                            <?php elseif ($block['type'] === 'form'): ?>
                                <div class="bg-white border rounded p-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <input class="border rounded px-3 py-2" placeholder="Name" readonly>
                                        <input class="border rounded px-3 py-2" placeholder="Phone" readonly>
                                    </div>
                                    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded w-full">Submit</button>
                                </div>
                            <?php elseif ($block['type'] === 'text'): ?>
                                <div class="bg-white p-4 rounded">
                                    <p class="text-gray-700"><?= htmlspecialchars($block['data']['content'] ?? 'Text content...') ?></p>
                                </div>
                            <?php else: ?>
                                <div class="bg-white p-4 rounded">
                                    <p class="text-gray-500 italic">[<?= ucfirst($block['type']) ?> block content]</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Hidden content field -->
        <input type="hidden" name="content" id="content-input" value="<?= htmlspecialchars(json_encode($blocks)) ?>">
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
                    <?= ($page['is_active'] ?? 0) ? 'checked' : '' ?>
                    class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded"
                >
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Page is active (can receive traffic)
                </label>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Status</h3>
                    <p class="text-sm text-gray-600">Current status: <span class="font-medium capitalize"><?= $page['status'] ?? 'draft' ?></span></p>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" name="action" value="save_draft" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Save as Draft
                    </button>
                    <button type="submit" name="action" value="publish" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        Publish Page
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end space-x-4">
        <a href="/admin/landing-pages" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" name="action" value="save" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Save Changes
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

<!-- Block Editor Modal -->
<div id="block-editor-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <form id="block-editor-form">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="editor-title">Edit Block</h3>
                <button type="button" class="close-editor text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="editor-content" class="space-y-4">
                <!-- Dynamic content based on block type -->
            </div>

            <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" class="cancel-edit px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="save-block px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg">
                    Save Block
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Block management variables
let blocks = <?= json_encode($blocks) ?>;
let currentEditingIndex = -1;

// Modal management
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Add block buttons
document.getElementById('add-block-btn')?.addEventListener('click', () => showModal('block-modal'));
document.getElementById('add-first-block-btn')?.addEventListener('click', () => showModal('block-modal'));

document.querySelectorAll('.close-modal').forEach(btn => {
    btn.addEventListener('click', () => hideModal('block-modal'));
});

document.querySelectorAll('.close-editor').forEach(btn => {
    btn.addEventListener('click', () => hideModal('block-editor-modal'));
});

// Block selection
document.querySelectorAll('.block-add').forEach(btn => {
    btn.addEventListener('click', function() {
        const blockType = this.dataset.type;
        addNewBlock(blockType);
        hideModal('block-modal');
    });
});

// Add new block
function addNewBlock(type) {
    const newBlock = {
        type: type,
        data: getDefaultBlockData(type)
    };

    blocks.push(newBlock);
    updateContentBuilder();
    updateHiddenInput();

    // Auto-open editor for new block
    setTimeout(() => {
        const newIndex = blocks.length - 1;
        editBlock(newIndex);
    }, 100);
}

// Get default data for block type
function getDefaultBlockData(type) {
    switch (type) {
        case 'hero':
            return {
                headline: 'Welcome to PokerOps',
                subheadline: 'Join the ultimate poker community',
                background_image: '',
                cta_text: 'Get Started',
                cta_link: '#'
            };
        case 'form':
            return {
                title: 'Join Our Community',
                description: 'Sign up to receive exclusive offers and updates',
                button_text: 'Sign Up Now'
            };
        case 'offers':
            return {
                title: 'Why Choose Us?',
                items: [
                    { icon: '🎯', title: 'Premium Experience', description: 'Top-tier poker action' },
                    { icon: '💰', title: 'Cash Prizes', description: 'Win big every week' }
                ]
            };
        case 'faq':
            return {
                title: 'Frequently Asked Questions',
                items: [
                    { question: 'How do I join?', answer: 'Simply fill out the form above!' }
                ]
            };
        case 'text':
            return {
                content: 'Your text content here...',
                background_color: 'white',
                text_align: 'left'
            };
        default:
            return {};
    }
}

// Edit block
document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-block')) {
        const blockItem = e.target.closest('.block-item');
        const index = parseInt(blockItem.dataset.index);
        editBlock(index);
    }
});

// Edit block function
function editBlock(index) {
    currentEditingIndex = index;
    const block = blocks[index];

    document.getElementById('editor-title').textContent = `Edit ${block.type.charAt(0).toUpperCase() + block.type.slice(1)} Block`;

    const editorContent = document.getElementById('editor-content');
    editorContent.innerHTML = getEditorHTML(block.type, block.data);

    showModal('block-editor-modal');
}

// Get editor HTML for block type
function getEditorHTML(type, data) {
    switch (type) {
        case 'hero':
            return `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Headline</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="headline" value="${data.headline || ''}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subheadline</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="subheadline" rows="3">${data.subheadline || ''}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CTA Button Text</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="cta_text" value="${data.cta_text || ''}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CTA Link</label>
                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="cta_link" value="${data.cta_link || ''}">
                </div>
            `;
        case 'form':
            return `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Form Title</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="title" value="${data.title || ''}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="description" rows="3">${data.description || ''}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="button_text" value="${data.button_text || ''}">
                </div>
            `;
        case 'text':
            return `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="content" rows="6">${data.content || ''}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="background_color">
                        <option value="white" ${data.background_color === 'white' ? 'selected' : ''}>White</option>
                        <option value="gray" ${data.background_color === 'gray' ? 'selected' : ''}>Gray</option>
                        <option value="blue" ${data.background_color === 'blue' ? 'selected' : ''}>Blue</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Text Alignment</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg" name="text_align">
                        <option value="left" ${data.text_align === 'left' ? 'selected' : ''}>Left</option>
                        <option value="center" ${data.text_align === 'center' ? 'selected' : ''}>Center</option>
                        <option value="right" ${data.text_align === 'right' ? 'selected' : ''}>Right</option>
                    </select>
                </div>
            `;
        default:
            return '<p class="text-gray-600">Editor not available for this block type.</p>';
    }
}

// Save block
document.getElementById('block-editor-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const updatedData = {};

    for (let [key, value] of formData.entries()) {
        updatedData[key] = value;
    }

    blocks[currentEditingIndex].data = updatedData;
    updateContentBuilder();
    updateHiddenInput();
    hideModal('block-editor-modal');
});

// Delete block
document.addEventListener('click', function(e) {
    if (e.target.closest('.delete-block')) {
        const blockItem = e.target.closest('.block-item');
        const index = parseInt(blockItem.dataset.index);

        if (confirm('Are you sure you want to delete this block?')) {
            blocks.splice(index, 1);
            updateContentBuilder();
            updateHiddenInput();
        }
    }
});

// Update content builder display
function updateContentBuilder() {
    const container = document.getElementById('content-builder');

    if (blocks.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No blocks yet</h3>
                <p class="text-gray-600 mb-4">Add blocks like hero sections, forms, offers, and more</p>
                <button type="button" id="add-first-block-btn" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add First Block
                </button>
            </div>
        `;
        return;
    }

    let html = '';
    blocks.forEach((block, index) => {
        html += `
            <div class="block-item bg-gray-50 border border-gray-200 rounded-lg p-4 relative" data-type="${block.type}" data-index="${index}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <span class="block-type-badge px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            ${block.type.charAt(0).toUpperCase() + block.type.slice(1)}
                        </span>
                        <span class="ml-2 text-sm text-gray-600">Block #${index + 1}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="move-block text-gray-400 hover:text-gray-600" title="Move">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                        </button>
                        <button type="button" class="edit-block text-blue-600 hover:text-blue-800" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button type="button" class="delete-block text-red-600 hover:text-red-800" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="block-preview text-sm text-gray-600">
                    ${getBlockPreviewHTML(block)}
                </div>
            </div>
        `;
    });

    container.innerHTML = html;

    // Re-attach event listeners
    document.getElementById('add-first-block-btn')?.addEventListener('click', () => showModal('block-modal'));
}

// Get preview HTML for block
function getBlockPreviewHTML(block) {
    switch (block.type) {
        case 'hero':
            return `
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 rounded">
                    <h2 class="text-xl font-bold mb-2">${block.data.headline || 'Hero Headline'}</h2>
                    <p class="mb-3">${block.data.subheadline || 'Hero subheadline...'}</p>
                    <button class="bg-white text-blue-600 px-4 py-2 rounded">${block.data.cta_text || 'CTA Button'}</button>
                </div>
            `;
        case 'form':
            return `
                <div class="bg-white border rounded p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <input class="border rounded px-3 py-2" placeholder="Name" readonly>
                        <input class="border rounded px-3 py-2" placeholder="Phone" readonly>
                    </div>
                    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded w-full">${block.data.button_text || 'Submit'}</button>
                </div>
            `;
        case 'text':
            return `
                <div class="bg-${block.data.background_color || 'white'} p-4 rounded">
                    <p class="text-gray-700 text-${block.data.text_align || 'left'}">${block.data.content || 'Text content...'}</p>
                </div>
            `;
        default:
            return `<div class="bg-white p-4 rounded"><p class="text-gray-500 italic">[${block.type.charAt(0).toUpperCase() + block.type.slice(1)} block content]</p></div>`;
    }
}

// Update hidden input with JSON
function updateHiddenInput() {
    document.getElementById('content-input').value = JSON.stringify(blocks);
}

// Slug generation
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');

    document.getElementById('slug').value = slug;
});

// Initialize
updateHiddenInput();
</script>
