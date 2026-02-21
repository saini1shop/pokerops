<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Create Landing Page</h1>
            <p class="text-gray-300 mt-1">Build a new marketing page to collect leads</p>
        </div>
        <a href="/admin/landing-pages" class="text-gray-400 hover:text-gray-300 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Pages
        </a>
    </div>
</div>

<form action="/admin/landing-pages" method="POST" class="space-y-6">
    <!-- Basic Information -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Basic Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-1">
                    Page Title *
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    required 
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    placeholder="e.g., Poker Tournament 2025"
                >
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-300 mb-1">
                    URL Slug *
                </label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 text-sm text-gray-300 bg-gray-600 border border-r-0 border-gray-600 rounded-l-lg">
                        pokerops.in/
                    </span>
                    <input 
                        type="text" 
                        id="slug" 
                        name="slug" 
                        required 
                        class="flex-1 min-w-0 px-3 py-2 bg-gray-700 border border-gray-600 rounded-none rounded-r-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="tournament-2025"
                    >
                </div>
                <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers, and hyphens only</p>
            </div>
        </div>

        <div class="mt-6">
            <label for="tracking_code" class="block text-sm font-medium text-gray-300 mb-1">
                Tracking Code (Optional)
            </label>
            <textarea 
                id="tracking_code" 
                name="tracking_code" 
                rows="4" 
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500 font-mono text-sm"
                placeholder="<script>
// Google Analytics, Facebook Pixel, etc.
</script>"
            ></textarea>
            <p class="text-xs text-gray-400 mt-1">Custom tracking scripts (Google Analytics, Facebook Pixel, etc.)</p>
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-300 mb-1">
                Description
            </label>
            <textarea 
                id="description" 
                name="description" 
                rows="3" 
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                placeholder="Brief description of this landing page..."
            ></textarea>
        </div>
    </div>

    <!-- Targeting & Attribution -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Targeting & Attribution</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="campaign_id" class="block text-sm font-medium text-gray-300 mb-1">
                    Campaign *
                </label>
                <select id="campaign_id" name="campaign_id" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Select a campaign...</option>
                    <?php foreach ($campaigns as $campaign): ?>
                        <option value="<?= $campaign['id'] ?>">
                            <?= htmlspecialchars($campaign['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-gray-400 mt-1">Campaign attribution is required for tracking and analytics</p>
            </div>

            <div>
                <label for="target_state" class="block text-sm font-medium text-gray-300 mb-1">
                    Target State (Optional)
                </label>
                <select id="target_state" name="target_state" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
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
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Page Content</h2>

        <!-- Content Builder Placeholder -->
        <div id="content-builder" class="space-y-4">
            <div class="text-center py-12 border-2 border-dashed border-gray-600 rounded-lg">
                <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-white mb-2">Start Building Your Page</h3>
                <p class="text-gray-400 mb-4">Add blocks like hero sections, forms, offers, and more</p>
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
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Settings</h2>

        <div class="space-y-4">
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active" 
                    checked 
                    class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-600 rounded bg-gray-700"
                >
                <label for="is_active" class="ml-2 block text-sm text-gray-300">
                    Page is active (can receive traffic)
                </label>
            </div>

            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="auto_publish" 
                    name="auto_publish" 
                    class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-600 rounded bg-gray-700"
                >
                <label for="auto_publish" class="ml-2 block text-sm text-gray-300">
                    Publish immediately after creation
                </label>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end space-x-4">
        <a href="/admin/landing-pages" class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700">
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
    <div class="relative top-20 mx-auto p-5 border border-gray-700 w-96 shadow-lg rounded-md bg-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-white">Add Block</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="space-y-3">
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-700 border border-gray-600 flex items-center" data-type="hero">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded flex items-center justify-center">
                        <span class="text-white font-bold text-sm">🎯</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-white">Hero Section</div>
                    <div class="text-sm text-gray-400">Eye-catching headline with call-to-action</div>
                </div>
            </button>
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-700 border border-gray-600 flex items-center" data-type="form">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-green-100 rounded flex items-center justify-center">
                        <span class="text-green-600 font-bold text-sm">📝</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-white">Contact Form</div>
                    <div class="text-sm text-gray-400">Lead capture form with WhatsApp consent</div>
                </div>
            </button>
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-700 border border-gray-600 flex items-center" data-type="offers">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-yellow-100 rounded flex items-center justify-center">
                        <span class="text-yellow-600 font-bold text-sm">🎁</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-white">Offers/Benefits</div>
                    <div class="text-sm text-gray-400">Highlight features and benefits</div>
                </div>
            </button>
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-700 border border-gray-600 flex items-center" data-type="faq">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-purple-100 rounded flex items-center justify-center">
                        <span class="text-purple-600 font-bold text-sm">❓</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-white">FAQ Section</div>
                    <div class="text-sm text-gray-400">Frequently asked questions with answers</div>
                </div>
            </button>
            <button type="button" class="block-add w-full text-left p-4 rounded-lg hover:bg-gray-700 border border-gray-600 flex items-center" data-type="text">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                        <span class="text-gray-600 font-bold text-sm">📄</span>
                    </div>
                </div>
                <div>
                    <div class="font-medium text-white">Text Block</div>
                    <div class="text-sm text-gray-400">Simple text content with styling options</div>
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

// Block management variables
let blocks = [];

// Modal management
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Add block buttons
document.getElementById('add-block-btn')?.addEventListener('click', () => showModal('block-modal'));

document.querySelectorAll('.close-modal').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('block-modal').classList.add('hidden');
    });
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

// Update content builder display
function updateContentBuilder() {
    const container = document.getElementById('content-builder');
    
    if (blocks.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 border-2 border-dashed border-gray-600 rounded-lg">
                <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-white mb-2">Start Building Your Page</h3>
                <p class="text-gray-400 mb-4">Add blocks like hero sections, forms, offers, and more</p>
                <button type="button" id="add-block-btn" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Block
                </button>
            </div>
        `;
        // Re-attach event listener
        document.getElementById('add-block-btn')?.addEventListener('click', () => showModal('block-modal'));
        return;
    }
    
    let html = '';
    blocks.forEach((block, index) => {
        html += `
            <div class="block-item bg-gray-700 border border-gray-600 rounded-lg p-4 relative" data-type="${block.type}" data-index="${index}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <span class="block-type-badge px-2 py-1 text-xs font-medium rounded-full bg-blue-900 text-blue-300">
                            ${block.type.charAt(0).toUpperCase() + block.type.slice(1)}
                        </span>
                        <span class="ml-2 text-sm text-gray-300">Block #${index + 1}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="edit-block text-blue-400 hover:text-blue-300" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button type="button" class="delete-block text-red-400 hover:text-red-300" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="block-preview text-sm text-gray-300">
                    ${getBlockPreviewHTML(block)}
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    
    // Re-attach event listeners for edit and delete buttons
    attachBlockEventListeners();
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
                <div class="bg-gray-600 border rounded p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <input class="border border-gray-500 rounded px-3 py-2 bg-gray-700 text-white" placeholder="Name" readonly>
                        <input class="border border-gray-500 rounded px-3 py-2 bg-gray-700 text-white" placeholder="Phone" readonly>
                    </div>
                    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded w-full">${block.data.button_text || 'Submit'}</button>
                </div>
            `;
        case 'text':
            return `
                <div class="bg-gray-600 p-4 rounded">
                    <p class="text-white text-${block.data.text_align || 'left'}">${block.data.content || 'Text content...'}</p>
                </div>
            `;
        default:
            return `<div class="bg-gray-600 p-4 rounded"><p class="text-gray-400 italic">[${block.type.charAt(0).toUpperCase() + block.type.slice(1)} block content]</p></div>`;
    }
}

// Attach event listeners for edit and delete buttons
function attachBlockEventListeners() {
    document.querySelectorAll('.edit-block').forEach(btn => {
        btn.addEventListener('click', function() {
            const blockItem = this.closest('.block-item');
            const index = parseInt(blockItem.dataset.index);
            editBlock(index);
        });
    });
    
    document.querySelectorAll('.delete-block').forEach(btn => {
        btn.addEventListener('click', function() {
            const blockItem = this.closest('.block-item');
            const index = parseInt(blockItem.dataset.index);
            
            if (confirm('Are you sure you want to delete this block?')) {
                blocks.splice(index, 1);
                updateContentBuilder();
                updateHiddenInput();
            }
        });
    });
}

// Edit block (placeholder - will implement modal later)
function editBlock(index) {
    const block = blocks[index];
    alert(`Edit ${block.type} block - implementation coming soon!`);
}

// Update hidden input with JSON
function updateHiddenInput() {
    document.getElementById('content-input').value = JSON.stringify(blocks);
}
</script>
