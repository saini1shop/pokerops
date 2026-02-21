<?php
/**
 * Public Landing Page Renderer
 * @var array $page
 * @var array $blocks
 */

// Parse page content JSON
$content = json_decode($page['content'], true) ?? [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page['description'] ?? '') ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
    
    <!-- Tracking Code -->
    <?= $page['tracking_code'] ?? '' ?>
</head>
<body class="bg-gray-50 text-gray-900">
    <!-- Landing Page Content -->
    <main id="landing-content">
        <?php foreach ($blocks as $block): ?>
            <?php 
            $blockContent = json_decode($block['content'], true) ?? [];
            $blockType = $block['block_type'];
            
            // Render based on block type
            switch ($blockType):
                case 'hero':
                    include __DIR__ . '/blocks/hero.php';
                    break;
                case 'offers':
                    include __DIR__ . '/blocks/offers.php';
                    break;
                case 'faq':
                    include __DIR__ . '/blocks/faq.php';
                    break;
                case 'form':
                    include __DIR__ . '/blocks/form.php';
                    break;
                case 'text':
                default:
                    include __DIR__ . '/blocks/text.php';
                    break;
            endswitch;
            ?>
        <?php endforeach; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-6 text-center text-sm">
        <p>&copy; <?= date('Y') ?> PokerOps.in - India's Poker Community</p>
    </footer>

    <!-- Form handling script -->
    <script>
        document.querySelectorAll('form[data-landing-form]').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
                
                try {
                    const formData = new FormData(form);
                    const response = await fetch('/api/landing/signup', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        form.innerHTML = '<div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-lg">Thank you! We\'ll contact you shortly.</div>';
                    } else {
                        alert(result.message || 'Something went wrong. Please try again.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                } catch (err) {
                    alert('Network error. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            });
        });
    </script>
</body>
</html>
