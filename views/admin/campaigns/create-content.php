<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Create Campaign</h1>
            <p class="text-gray-300 mt-1">Set up a new marketing campaign</p>
        </div>
        <a href="/admin/campaigns" class="text-gray-400 hover:text-gray-300 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Campaigns
        </a>
    </div>
</div>

<form action="/admin/campaigns" method="POST" class="space-y-6">
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
                ></textarea>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-300 mb-1">
                    Status
                </label>
                <select id="status" name="status" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="active">Active - Can be used for landing pages</option>
                    <option value="inactive">Inactive - Hidden from selection</option>
                    <option value="archived">Archived - Historical reference only</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Active campaigns can be selected when creating landing pages</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end space-x-4">
        <a href="/admin/campaigns" class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700">
            Cancel
        </a>
        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Create Campaign
        </button>
    </div>
</form>
