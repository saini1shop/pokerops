<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Campaign</h1>
            <p class="text-gray-300 mt-1">Update campaign details and settings</p>
        </div>
        <a href="/admin/campaigns" class="text-gray-400 hover:text-gray-300 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Campaigns
        </a>
    </div>
</div>

<form action="/admin/campaigns/<?= $campaign['id'] ?>" method="POST" class="space-y-6">
    <!-- Basic Information -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Basic Information</h2>

        <div class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">
                    Campaign Name *
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    value="<?= htmlspecialchars($campaign['name'] ?? '') ?>"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    placeholder="e.g., Poker Tournament 2025"
                >
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-1">
                    Description
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    placeholder="Brief description of this campaign..."
                ><?= htmlspecialchars($campaign['description'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-300 mb-1">
                    Status
                </label>
                <select id="status" name="status" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="active" <?= ($campaign['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active - Can be used for landing pages</option>
                    <option value="inactive" <?= ($campaign['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive - Hidden from selection</option>
                    <option value="archived" <?= ($campaign['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived - Historical reference only</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Active campaigns can be selected when creating landing pages</p>
            </div>
        </div>
    </div>

    <!-- Related Landing Pages -->
    <?php if (!empty($landingPages)): ?>
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Related Landing Pages</h2>
        <p class="text-gray-400 mb-4">This campaign is currently used by the following landing pages:</p>

        <div class="space-y-2">
            <?php foreach ($landingPages as $page): ?>
                <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded bg-gray-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-white">
                                <?= htmlspecialchars($page['title']) ?>
                            </div>
                            <div class="text-sm text-gray-400">
                                /<?= htmlspecialchars($page['slug']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= match($page['status']) {
                            'published' => 'bg-green-900 text-green-300',
                            'draft' => 'bg-yellow-900 text-yellow-300',
                            default => 'bg-gray-700 text-gray-300'
                        } ?>">
                            <?= ucfirst($page['status'] ?? 'draft') ?>
                        </span>
                        <a href="/admin/landing-pages/edit/<?= $page['id'] ?>" class="text-blue-400 hover:text-blue-300 text-sm">
                            Edit
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 p-3 bg-yellow-900 border border-yellow-700 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-300">
                        <strong>Note:</strong> This campaign cannot be deleted while it has associated landing pages.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="flex items-center justify-between">
        <div>
            <?php if (empty($landingPages)): ?>
                <form method="POST" action="/admin/campaigns/<?= $campaign['id'] ?>/delete" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this campaign? This action cannot be undone.')">
                    <button type="submit" class="text-red-400 hover:text-red-300 px-4 py-2 border border-red-700 rounded-lg hover:bg-red-900">
                        Delete Campaign
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <div class="flex space-x-4">
            <a href="/admin/campaigns" class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700">
                Cancel
            </a>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Campaign
            </button>
        </div>
    </div>
</form>
