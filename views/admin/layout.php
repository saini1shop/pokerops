<?php
/**
 * Admin Dashboard Layout
 */
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - PokerOps Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">
    <!-- Mobile Header -->
    <div class="lg:hidden bg-gray-800 border-b border-gray-700">
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-brand-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="font-bold text-lg">PokerOps</span>
            </div>
            <button onclick="toggleMobileMenu()" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden border-t border-gray-700">
            <nav class="px-2 py-3 space-y-1">
                <?php include __DIR__ . '/nav-links.php'; ?>
            </nav>
        </div>
    </div>

    <div class="flex min-h-screen">
        <!-- Sidebar (Desktop) -->
        <div class="hidden lg:flex lg:flex-col lg:w-64 bg-gray-800 border-r border-gray-700 fixed h-full">
            <!-- Logo -->
            <div class="flex items-center h-16 px-6 border-b border-gray-700">
                <div class="w-10 h-10 bg-brand-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="font-bold text-lg">PokerOps</span>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <?php include __DIR__ . '/nav-links.php'; ?>
            </nav>

            <!-- User Info -->
            <div class="border-t border-gray-700 p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium"><?= htmlspecialchars($user['name']) ?></p>
                        <p class="text-xs text-gray-400"><?= $user['role'] ?></p>
                    </div>
                </div>
                <a href="/admin/logout" class="mt-3 flex items-center text-sm text-gray-400 hover:text-red-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Top Bar -->
            <div class="hidden lg:flex items-center justify-between h-16 px-8 bg-gray-800 border-b border-gray-700">
                <h1 class="text-xl font-semibold"><?= $title ?? 'Dashboard' ?></h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-400"><?= date('D, M j, Y') ?></span>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-4 lg:p-8">
                <?php include $contentView; ?>
            </main>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }
    </script>
</body>
</html>
