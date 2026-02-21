<?php
/**
 * WhatsApp Compose View
 * @var array $templates
 * @var array $recentPlayers
 * @var array $communities
 * @var array $states
 * @var array $recentMessages
 * @var array $user
 */
?>

<!-- Tabs -->
<div class="mb-6 border-b border-gray-700">
    <nav class="flex space-x-8">
        <a href="/admin/whatsapp" class="border-b-2 border-brand-500 text-brand-500 py-4 px-1 text-sm font-medium">
            Send Message
        </a>
        <a href="/admin/whatsapp/logs" class="border-b-2 border-transparent text-gray-400 hover:text-gray-300 py-4 px-1 text-sm font-medium">
            Message Logs
        </a>
    </nav>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Left: Composer -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h2 class="text-lg font-semibold mb-6">Send WhatsApp Message</h2>
            
            <form id="whatsapp-form" class="space-y-6">
                <!-- Recipients Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Recipients</label>
                    
                    <!-- Search Players -->
                    <div class="relative mb-4">
                        <input 
                            type="text" 
                            id="player-search" 
                            placeholder="Search players by name or phone..."
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-white placeholder-gray-400"
                        >
                        <div id="search-results" class="absolute z-10 w-full mt-1 bg-gray-700 border border-gray-600 rounded-lg shadow-xl max-h-60 overflow-y-auto hidden">
                            <!-- Search results populated via JS -->
                        </div>
                    </div>
                    
                    <!-- Selected Players -->
                    <div id="selected-players" class="space-y-2 mb-4">
                        <!-- Selected players will appear here -->
                    </div>
                    
                    <!-- Filters -->
                    <div class="flex gap-3 mb-4">
                        <select id="filter-state" class="px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-sm text-gray-300">
                            <option value="">All States</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= $state['id'] ?>"><?= htmlspecialchars($state['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <select id="filter-community" class="px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-sm text-gray-300">
                            <option value="">All Communities</option>
                            <?php foreach ($communities as $community): ?>
                                <option value="<?= $community['id'] ?>"><?= htmlspecialchars($community['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="button" id="load-filtered" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm">
                            Load Players
                        </button>
                    </div>
                </div>
                
                <!-- Template Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Message Template</label>
                    <select name="template" id="template-select" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-white">
                        <option value="">Select a template...</option>
                        <?php foreach ($templates as $key => $name): ?>
                            <option value="<?= $key ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Templates must be pre-approved in AiSensy dashboard</p>
                </div>
                
                <!-- Template Variables -->
                <div id="template-variables" class="hidden space-y-4">
                    <!-- Dynamic fields based on template selection -->
                </div>
                
                <!-- Campaign Attribution (optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Campaign (Optional)</label>
                    <select name="campaign_id" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-white">
                        <option value="">No campaign attribution</option>
                        <!-- Campaigns loaded via API -->
                    </select>
                </div>
                
                <!-- Send Button -->
                <div class="flex items-center justify-between pt-4">
                    <div class="text-sm text-gray-400">
                        <span id="recipient-count">0</span> recipients selected
                    </div>
                    <button 
                        type="submit" 
                        id="send-btn"
                        disabled
                        class="bg-brand-600 hover:bg-brand-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send Messages
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Quick Templates Reference -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h3 class="font-semibold mb-4">Template Variables</h3>
            <div class="space-y-2 text-sm text-gray-400">
                <p><code class="bg-gray-700 px-2 py-1 rounded">{{1}}</code> - Player name (auto-filled)</p>
                <p><code class="bg-gray-700 px-2 py-1 rounded">{{2}}</code> - Custom variable (community link, tournament name, etc.)</p>
                <p><code class="bg-gray-700 px-2 py-1 rounded">{{3}}</code> - Additional custom variable</p>
            </div>
        </div>
    </div>
    
    <!-- Right: Recent Activity -->
    <div>
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h3 class="font-semibold mb-4">Recent Messages</h3>
            
            <?php if (empty($recentMessages)): ?>
                <p class="text-gray-400 text-sm">No messages sent yet</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach (array_slice($recentMessages, 0, 10) as $msg): ?>
                        <div class="flex items-start space-x-3 p-3 bg-gray-700/50 rounded-lg">
                            <div class="flex-shrink-0">
                                <?php if ($msg['status'] === 'sent' || $msg['status'] === 'delivered'): ?>
                                    <span class="w-2 h-2 bg-green-500 rounded-full block"></span>
                                <?php elseif ($msg['status'] === 'read'): ?>
                                    <span class="w-2 h-2 bg-blue-500 rounded-full block"></span>
                                <?php elseif ($msg['status'] === 'failed'): ?>
                                    <span class="w-2 h-2 bg-red-500 rounded-full block"></span>
                                <?php else: ?>
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full block"></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate"><?= htmlspecialchars($msg['player_name']) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($msg['template_name']) ?></p>
                                <p class="text-xs text-gray-500 mt-1"><?= date('M j, g:i A', strtotime($msg['created_at'])) ?></p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full <?= match($msg['status']) {
                                'sent' => 'bg-green-600/20 text-green-500',
                                'delivered' => 'bg-green-600/20 text-green-500',
                                'read' => 'bg-blue-600/20 text-blue-500',
                                'failed' => 'bg-red-600/20 text-red-500',
                                default => 'bg-yellow-600/20 text-yellow-500'
                            } ?>">
                                <?= $msg['status'] ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <a href="/admin/whatsapp/logs" class="block mt-4 text-center text-sm text-brand-500 hover:text-brand-400">
                    View All Logs →
                </a>
            <?php endif; ?>
        </div>
        
        <!-- AiSensy Status -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 mt-6">
            <h3 class="font-semibold mb-4">Provider Status</h3>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                <div>
                    <p class="text-sm font-medium">AiSensy Connected</p>
                    <p class="text-xs text-gray-400">Template messages active</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Player search functionality
let selectedPlayers = [];

const playerSearch = document.getElementById('player-search');
const searchResults = document.getElementById('search-results');
const selectedPlayersDiv = document.getElementById('selected-players');
const recipientCount = document.getElementById('recipient-count');
const sendBtn = document.getElementById('send-btn');

// Search players
playerSearch.addEventListener('input', async (e) => {
    const query = e.target.value.trim();
    if (query.length < 2) {
        searchResults.classList.add('hidden');
        return;
    }
    
    const stateId = document.getElementById('filter-state').value;
    const communityId = document.getElementById('filter-community').value;
    
    try {
        const params = new URLSearchParams({ q: query });
        if (stateId) params.append('state_id', stateId);
        if (communityId) params.append('community_id', communityId);
        
        const response = await fetch(`/admin/whatsapp/search-players?${params}`);
        const data = await response.json();
        
        if (data.players && data.players.length > 0) {
            searchResults.innerHTML = data.players.map(p => `
                <div class="p-3 hover:bg-gray-600 cursor-pointer flex items-center" onclick="addPlayer(${p.id}, '${p.name}', '${p.phone}')">
                    <div class="w-8 h-8 bg-brand-600 rounded-full flex items-center justify-center mr-3">
                        <span class="text-sm">${p.name.charAt(0).toUpperCase()}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium">${p.name}</p>
                        <p class="text-xs text-gray-400">${p.phone}${p.city ? ' · ' + p.city : ''}</p>
                    </div>
                </div>
            `).join('');
            searchResults.classList.remove('hidden');
        } else {
            searchResults.innerHTML = '<div class="p-3 text-gray-400">No players found</div>';
            searchResults.classList.remove('hidden');
        }
    } catch (err) {
        console.error('Search failed:', err);
    }
});

// Hide search results when clicking outside
document.addEventListener('click', (e) => {
    if (!playerSearch.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('hidden');
    }
});

// Add player to selection
function addPlayer(id, name, phone) {
    if (selectedPlayers.find(p => p.id === id)) return;
    
    selectedPlayers.push({ id, name, phone });
    renderSelectedPlayers();
    playerSearch.value = '';
    searchResults.classList.add('hidden');
}

// Remove player from selection
function removePlayer(id) {
    selectedPlayers = selectedPlayers.filter(p => p.id !== id);
    renderSelectedPlayers();
}

// Render selected players
function renderSelectedPlayers() {
    selectedPlayersDiv.innerHTML = selectedPlayers.map(p => `
        <div class="flex items-center justify-between p-3 bg-brand-600/20 border border-brand-600/50 rounded-lg">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-brand-600 rounded-full flex items-center justify-center mr-3">
                    <span class="text-sm">${p.name.charAt(0).toUpperCase()}</span>
                </div>
                <div>
                    <p class="text-sm font-medium">${p.name}</p>
                    <p class="text-xs text-gray-400">${p.phone}</p>
                </div>
            </div>
            <button type="button" onclick="removePlayer(${p.id})" class="text-red-400 hover:text-red-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `).join('');
    
    recipientCount.textContent = selectedPlayers.length;
    sendBtn.disabled = selectedPlayers.length === 0;
}

// Template variable fields
document.getElementById('template-select').addEventListener('change', (e) => {
    const template = e.target.value;
    const variablesDiv = document.getElementById('template-variables');
    
    if (!template) {
        variablesDiv.classList.add('hidden');
        return;
    }
    
    // Show variable inputs based on template type
    let fields = '';
    switch (template) {
        case 'community_invite':
            fields = `
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Invite Link *</label>
                    <input type="url" name="invite_link" required 
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white"
                        placeholder="https://chat.whatsapp.com/...">
                </div>
            `;
            break;
        case 'tournament_reminder':
            fields = `
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Tournament Name *</label>
                    <input type="text" name="tournament_name" required 
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Date/Time *</label>
                    <input type="text" name="tournament_datetime" required 
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white"
                        placeholder="Tomorrow at 7 PM">
                </div>
            `;
            break;
        case 'event_promo':
            fields = `
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Event Details *</label>
                    <input type="text" name="event_details" required 
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white"
                        placeholder="Cash game this Friday with 50% bonus chips">
                </div>
            `;
            break;
        case 'checkin_thanks':
            fields = `
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Venue Name *</label>
                    <input type="text" name="venue_name" required 
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white">
                </div>
            `;
            break;
    }
    
    variablesDiv.innerHTML = fields;
    variablesDiv.classList.remove('hidden');
});

// Form submission
document.getElementById('whatsapp-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    if (selectedPlayers.length === 0) {
        alert('Please select at least one recipient');
        return;
    }
    
    const formData = new FormData(e.target);
    formData.append('player_ids', JSON.stringify(selectedPlayers.map(p => p.id)));
    
    sendBtn.disabled = true;
    sendBtn.innerHTML = `
        <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Sending...
    `;
    
    try {
        const isBulk = selectedPlayers.length > 1;
        const endpoint = isBulk ? '/admin/whatsapp/send-bulk' : '/admin/whatsapp/send';
        
        const response = await fetch(endpoint, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(`Messages sent successfully!${isBulk ? ` (${result.summary?.sent || 0} sent)` : ''}`);
            selectedPlayers = [];
            renderSelectedPlayers();
            e.target.reset();
            document.getElementById('template-variables').classList.add('hidden');
        } else {
            alert('Error: ' + (result.error || 'Failed to send messages'));
        }
    } catch (err) {
        alert('Network error. Please try again.');
    } finally {
        sendBtn.disabled = selectedPlayers.length === 0;
        sendBtn.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Send Messages
        `;
    }
});
</script>
