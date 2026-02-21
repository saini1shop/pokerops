<?php
namespace App\Controllers;

use App\Database;
use PDO;

class LandingPageController {
    public function index(): void {
        $db = Database::getInstance();
        
        // Fetch all landing pages with basic stats
        $stmt = $db->prepare("
            SELECT lp.*, 
                   COUNT(ps.id) as signup_count,
                   COUNT(DISTINCT ps.player_id) as unique_signups
            FROM igp_landing_pages lp
            LEFT JOIN igp_player_signups ps ON lp.id = ps.landing_page_id
            GROUP BY lp.id
            ORDER BY lp.created_at DESC
        ");
        $stmt->execute();
        $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        include VIEWS_PATH . '/admin/landing-pages/index.php';
    }
    
    public function create(): void {
        $db = Database::getInstance();
        
        // Fetch campaigns for dropdown
        $stmt = $db->prepare("SELECT id, name FROM igp_campaigns WHERE status = 'active' ORDER BY name");
        $stmt->execute();
        $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Fetch states for dropdown
        $stmt = $db->prepare("SELECT id, name FROM igp_states ORDER BY name");
        $stmt->execute();
        $states = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        include VIEWS_PATH . '/admin/landing-pages/create.php';
    }
    
    public function store(): void {
        $db = Database::getInstance();
        
        // Validate required fields
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $campaignId = filter_input(INPUT_POST, 'campaign_id', FILTER_VALIDATE_INT);
        $targetState = filter_input(INPUT_POST, 'target_state', FILTER_VALIDATE_INT);
        $content = $_POST['content'] ?? '[]';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $autoPublish = isset($_POST['auto_publish']);
        
        $errors = [];
        
        if (empty($title)) {
            $errors[] = 'Page title is required';
        }
        
        if (empty($slug)) {
            $errors[] = 'URL slug is required';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            $errors[] = 'Slug can only contain lowercase letters, numbers, and hyphens';
        } else {
            // Check if slug already exists
            $stmt = $db->prepare("SELECT id FROM igp_landing_pages WHERE slug = ?");
            $stmt->execute([$slug]);
            if ($stmt->fetch()) {
                $errors[] = 'This URL slug is already taken';
            }
        }
        
        if (!empty($errors)) {
            // Store errors in session and redirect back
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: /admin/landing-pages/new');
            exit;
        }
        
        // Insert new landing page
        $stmt = $db->prepare("
            INSERT INTO igp_landing_pages 
            (title, slug, description, campaign_id, target_state_id, content, status, is_active, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $status = $autoPublish ? 'published' : 'draft';
        
        $stmt->execute([
            $title,
            $slug, 
            $description,
            $campaignId ?: null,
            $targetState ?: null,
            $content,
            $status,
            $isActive
        ]);
        
        $pageId = $db->lastInsertId();
        
        // Redirect to edit page or back to list
        $_SESSION['success_message'] = 'Landing page created successfully!';
        header('Location: /admin/landing-pages/edit/' . $pageId);
        exit;
    }
    
    public function edit(int $id): void {
        echo "Edit Landing Page {$id} - Coming Soon";
    }
    
    public function update(int $id): void {
        echo "Update Landing Page {$id} - Coming Soon";
    }
    
    public function publish(int $id): void {
        echo "Publish Landing Page {$id} - Coming Soon";
    }
}
