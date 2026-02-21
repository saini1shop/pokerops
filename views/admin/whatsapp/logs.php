<?php
/**
 * WhatsApp Logs View
 * @var array $logs
 * @var int $page
 * @var int $totalPages
 * @var int $total
 */

$title = 'WhatsApp Logs';
?>

<!-- Tabs -->
<div class="mb-6 border-b border-gray-700">
    <nav class="flex space-x-8">
        <a href="/admin/whatsapp" class="border-b-2 border-transparent text-gray-400 hover:text-gray-300 py-4 px-1 text-sm font-medium">
            Send Message
        </a>
        <a href="/admin/whatsapp/logs" class="border-b-2 border-brand-500 text-brand-500 py-4 px-1 text-sm font-medium">
            Message Logs
        </a>
    </nav>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <p class="text-xs text-gray-400">Total Sent</p>
        <p class="text-2xl font-bold"><?= number_format($total) ?></p>
    </div>
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <p class="text-xs text-gray-400">Delivered</p>
        <p class="text-2xl font-bold text-green-500">
            <?= number_format(array_filter($logs, fn($l) => in_array($l['status'], ['delivered', 'read'])) ? count(array_filter($logs, fn($l) => in_array($l['status'], ['delivered', 'read']))) : 0) ?>
        </p>
    </div>
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <p class="text-xs text-gray-400">Read</p>
        <p class="text-2xl font-bold text-blue-500">
            <?= number_format(array_filter($logs, fn($l) => $l['status'] === 'read') ? count(array_filter($logs, fn($l) => $l['status'] === 'read')) : 0) ?>
        </p>
    </div>
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <p class="text-xs text-gray-400">Failed</p>
        <p class="text-2xl font-bold text-red-500">
            <?= number_format(array_filter($logs, fn($l) => $l['status'] === 'failed') ? count(array_filter($logs, fn($l) => $l['status'] === 'failed')) : 0) ?>
        </p>
    </div>
</div>

<!-- Logs Table -->
<div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-400 uppercase">Player</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-400 uppercase">Template</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-400 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-400 uppercase">Provider ID</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-400 uppercase">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                            No messages found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-brand-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm"><?= strtoupper(substr($log['player_name'], 0, 1)) ?></span>
                                    </div>
                                    <div>
                                        <p class="font-medium"><?= htmlspecialchars($log['player_name']) ?></p>
                                        <p class="text-xs text-gray-400"><?= $log['phone'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-700 text-gray-300">
                                    <?= htmlspecialchars($log['template_name']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs rounded-full <?= match($log['status']) {
                                    'sent' => 'bg-green-600/20 text-green-500',
                                    'delivered' => 'bg-green-600/20 text-green-500',
                                    'read' => 'bg-blue-600/20 text-blue-500',
                                    'failed' => 'bg-red-600/20 text-red-500',
                                    default => 'bg-yellow-600/20 text-yellow-500'
                                } ?>">
                                    <?= $log['status'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400 font-mono">
                                <?= $log['provider_message_id'] ? substr($log['provider_message_id'], 0, 20) . '...' : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <?= date('M j, Y g:i A', strtotime($log['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="px-6 py-4 border-t border-gray-700 flex items-center justify-between">
            <p class="text-sm text-gray-400">
                Page <?= $page ?> of <?= $totalPages ?>
            </p>
            <div class="flex space-x-2">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm">
                        Previous
                    </a>
                <?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm">
                        Next
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
