<?php
/**
 * Dashboard Content
 * @var array $stats
 * @var array $recentSignups
 * @var array $recentCheckins
 * @var array $campaigns
 */
?>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    <!-- Total Players -->
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">Total Players</p>
                <p class="text-2xl font-bold"><?= number_format($stats['total_players']) ?></p>
            </div>
            <div class="w-10 h-10 bg-blue-600/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Players Today -->
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">New Today</p>
                <p class="text-2xl font-bold"><?= number_format($stats['players_today']) ?></p>
            </div>
            <div class="w-10 h-10 bg-green-600/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Signups Today -->
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">Signups Today</p>
                <p class="text-2xl font-bold"><?= number_format($stats['signups_today']) ?></p>
            </div>
            <div class="w-10 h-10 bg-purple-600/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Checkins -->
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">Active Check-ins</p>
                <p class="text-2xl font-bold"><?= number_format($stats['active_checkins']) ?></p>
            </div>
            <div class="w-10 h-10 bg-orange-600/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- WhatsApp Today -->
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">WhatsApp Sent</p>
                <p class="text-2xl font-bold"><?= number_format($stats['whatsapp_sent_today']) ?></p>
            </div>
            <div class="w-10 h-10 bg-emerald-600/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Upcoming Tournaments -->
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">Upcoming Events</p>
                <p class="text-2xl font-bold"><?= number_format($stats['upcoming_tournaments']) ?></p>
            </div>
            <div class="w-10 h-10 bg-red-600/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Two Column Layout -->
<div class="grid lg:grid-cols-3 gap-6">
    <!-- Left Column: Recent Activity -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Recent Signups -->
        <div class="bg-gray-800 rounded-xl border border-gray-700">
            <div class="p-4 border-b border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold">Recent Signups</h3>
                <a href="/admin/players" class="text-sm text-brand-500 hover:text-brand-400">View All</a>
            </div>
            <div class="p-4">
                <?php if (empty($recentSignups)): ?>
                    <p class="text-gray-400 text-center py-4">No recent signups</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($recentSignups as $signup): ?>
                            <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0">
                                <div>
                                    <p class="font-medium"><?= htmlspecialchars($signup['player_name'] ?? $signup['name']) ?></p>
                                    <p class="text-sm text-gray-400"><?= $signup['landing_page'] ? 'via ' . htmlspecialchars($signup['landing_page']) : 'Direct' ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm"><?= date('M j, g:i A', strtotime($signup['submitted_at'])) ?></p>
                                    <p class="text-xs text-gray-400"><?= $signup['utm_source'] ? htmlspecialchars($signup['utm_source']) : 'Organic' ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Check-ins -->
        <div class="bg-gray-800 rounded-xl border border-gray-700">
            <div class="p-4 border-b border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold">Recent Check-ins</h3>
                <a href="/admin/checkins" class="text-sm text-brand-500 hover:text-brand-400">View All</a>
            </div>
            <div class="p-4">
                <?php if (empty($recentCheckins)): ?>
                    <p class="text-gray-400 text-center py-4">No recent check-ins</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($recentCheckins as $checkin): ?>
                            <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-0">
                                <div>
                                    <p class="font-medium"><?= htmlspecialchars($checkin['player_name']) ?></p>
                                    <p class="text-sm text-gray-400"><?= htmlspecialchars($checkin['venue_name']) ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full <?= $checkin['status'] === 'checked_in' ? 'bg-green-600/20 text-green-500' : 'bg-gray-600/20 text-gray-400' ?>">
                                        <?= str_replace('_', ' ', $checkin['status']) ?>
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1"><?= date('g:i A', strtotime($checkin['checkin_time'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Campaigns -->
    <div class="space-y-6">
        <div class="bg-gray-800 rounded-xl border border-gray-700">
            <div class="p-4 border-b border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold">Active Campaigns</h3>
                <a href="/admin/campaigns" class="text-sm text-brand-500 hover:text-brand-400">Manage</a>
            </div>
            <div class="p-4">
                <?php if (empty($campaigns)): ?>
                    <p class="text-gray-400 text-center py-4">No active campaigns</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($campaigns as $campaign): ?>
                            <div class="p-3 bg-gray-700/50 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <p class="font-medium"><?= htmlspecialchars($campaign['name']) ?></p>
                                    <span class="text-xs text-gray-400"><?= $campaign['platform'] ?></span>
                                </div>
                                <p class="text-sm text-gray-400 mt-1"><?= htmlspecialchars($campaign['landing_page'] ?? 'No landing page') ?></p>
                                <div class="mt-2 flex items-center justify-between text-sm">
                                    <span class="text-gray-400"><?= number_format($campaign['signup_count']) ?> signups</span>
                                    <a href="/admin/campaigns" class="text-brand-500 hover:text-brand-400">Details →</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
            <h3 class="font-semibold mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="/admin/checkins" class="flex items-center p-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                    <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <span>Check in Player</span>
                </a>
                <a href="/admin/landing-pages/new" class="flex items-center p-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <span>New Landing Page</span>
                </a>
                <a href="/admin/players" class="flex items-center p-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <span>Add Player</span>
                </a>
            </div>
        </div>
    </div>
</div>
