<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-white">Campaigns</h1>
        <p class="text-gray-300 mt-1">Manage marketing campaigns for landing pages</p>
    </div>
    <a href="/admin/campaigns/new" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        New Campaign
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-900 rounded-lg">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-400">Total Campaigns</p>
                <p class="text-2xl font-bold text-white"><?= count($campaigns) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-green-900 rounded-lg">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-400">Active</p>
                <p class="text-2xl font-bold text-white">
                    <?= count(array_filter($campaigns, fn($c) => $c['status'] === 'active')) ?>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-900 rounded-lg">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-400">Landing Pages</p>
                <p class="text-2xl font-bold text-white">
                    <?= array_sum(array_column($campaigns, 'landing_pages_count')) ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Campaigns Table -->
<div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-700">
        <h2 class="text-lg font-semibold text-white">All Campaigns</h2>
    </div>

    <?php if (empty($campaigns)): ?>
        <div class="px-6 py-12 text-center">
            <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            <h3 class="text-lg font-medium text-white mb-2">No campaigns yet</h3>
            <p class="text-gray-400 mb-4">Create your first marketing campaign to organize landing pages</p>
            <a href="/admin/campaigns/new" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create First Campaign
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Campaign</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Landing Pages</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    <?php foreach ($campaigns as $campaign): ?>
                        <tr class="hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-gray-600 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">
                                            <?= htmlspecialchars($campaign['name'] ?? 'Unnamed Campaign') ?>
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            ID: <?= $campaign['id'] ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= match($campaign['status']) {
                                    'active' => 'bg-green-900 text-green-300',
                                    'inactive' => 'bg-yellow-900 text-yellow-300',
                                    'archived' => 'bg-gray-700 text-gray-300',
                                    default => 'bg-gray-700 text-gray-300'
                                } ?>">
                                    <?= ucfirst($campaign['status'] ?? 'inactive') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-white">
                                <?= $campaign['landing_pages_count'] ?? 0 ?> pages
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <?= date('M j, Y', strtotime($campaign['created_at'] ?? 'now')) ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="/admin/campaigns/edit/<?= $campaign['id'] ?>"
                                       class="text-blue-400 hover:text-blue-300">
                                        Edit
                                    </a>
                                    <?php if (($campaign['landing_pages_count'] ?? 0) == 0): ?>
                                        <form method="POST" action="/admin/campaigns/<?= $campaign['id'] ?>/delete" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this campaign?')">
                                            <button type="submit" class="text-red-400 hover:text-red-300">
                                                Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" action="/admin/campaigns/<?= $campaign['id'] ?>/toggle" class="inline">
                                        <button type="submit" class="text-green-400 hover:text-green-300">
                                            <?= ($campaign['status'] ?? 'inactive') === 'active' ? 'Deactivate' : 'Activate' ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
