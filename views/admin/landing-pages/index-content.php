<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-white">Landing Pages</h1>
        <p class="text-gray-300 mt-1">Create and manage your marketing landing pages</p>
    </div>
    <a href="/admin/landing-pages/new" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        New Page
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-900 rounded-lg">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-400">Total Pages</p>
                <p class="text-2xl font-bold text-white"><?= count($pages) ?></p>
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
                <p class="text-sm font-medium text-gray-400">Published</p>
                <p class="text-2xl font-bold text-white">
                    <?= count(array_filter($pages, fn($p) => $p['status'] === 'published')) ?>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-900 rounded-lg">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-400">Drafts</p>
                <p class="text-2xl font-bold text-white">
                    <?= count(array_filter($pages, fn($p) => $p['status'] === 'draft')) ?>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <div class="flex items-center">
            <div class="p-2 bg-purple-900 rounded-lg">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-400">Signups</p>
                <p class="text-2xl font-bold text-white">0</p>
            </div>
        </div>
    </div>
</div>

<!-- Pages Table -->
<div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-700">
        <h2 class="text-lg font-semibold text-white">All Pages</h2>
    </div>

    <?php if (empty($pages)): ?>
        <div class="px-6 py-12 text-center">
            <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-white mb-2">No landing pages yet</h3>
            <p class="text-gray-400 mb-4">Create your first landing page to start collecting leads</p>
            <a href="/admin/landing-pages/new" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create First Page
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Page</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    <?php foreach ($pages as $page): ?>
                        <tr class="hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-gray-600 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">
                                            <?= htmlspecialchars($page['title'] ?? 'Untitled') ?>
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            ID: <?= $page['id'] ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= match($page['status']) {
                                    'published' => 'bg-green-900 text-green-300',
                                    'draft' => 'bg-yellow-900 text-yellow-300',
                                    default => 'bg-gray-700 text-gray-300'
                                } ?>">
                                    <?= ucfirst($page['status'] ?? 'draft') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-white">
                                <code class="bg-gray-700 px-2 py-1 rounded text-xs text-gray-300">
                                    /<?= htmlspecialchars($page['slug'] ?? '') ?>
                                </code>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <?= date('M j, Y', strtotime($page['created_at'] ?? 'now')) ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <?php if (($page['status'] ?? 'draft') === 'published'): ?>
                                        <a href="/<?= htmlspecialchars($page['slug'] ?? '') ?>" target="_blank"
                                           class="text-green-400 hover:text-green-300">
                                            View
                                        </a>
                                    <?php endif; ?>
                                    <a href="/admin/landing-pages/edit/<?= $page['id'] ?>"
                                       class="text-blue-400 hover:text-blue-300">
                                        Edit
                                    </a>
                                    <form method="POST" action="/admin/landing-pages/<?= $page['id'] ?>/publish" class="inline">
                                        <button type="submit"
                                                class="text-<?= ($page['status'] ?? 'draft') === 'published' ? 'yellow' : 'green' ?>-400 hover:text-<?= ($page['status'] ?? 'draft') === 'published' ? 'yellow' : 'green' ?>-300">
                                            <?= ($page['status'] ?? 'draft') === 'published' ? 'Unpublish' : 'Publish' ?>
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
