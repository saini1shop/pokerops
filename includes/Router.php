<?php
namespace App;

class Router {
    
    public function handleLandingPage(string $slug): void {
        $db = Database::getInstance();
        
        // Fetch landing page by slug
        $stmt = $db->prepare("SELECT lp.*, lpt.content_schema 
            FROM igp_landing_pages lp 
            JOIN igp_lp_templates lpt ON lp.template_id = lpt.id 
            WHERE lp.slug = ? AND lp.status = 'published' AND lp.is_active = 1");
        $stmt->execute([$slug]);
        $page = $stmt->fetch();
        
        if (!$page) {
            http_response_code(404);
            include VIEWS_PATH . '/public/404.php';
            return;
        }
        
        // Fetch blocks
        $stmt = $db->prepare("SELECT * FROM igp_landing_page_blocks 
            WHERE landing_page_id = ? AND is_active = 1 
            ORDER BY sort_order ASC");
        $stmt->execute([$page['id']]);
        $blocks = $stmt->fetchAll();
        
        // Log view (async, don't block)
        $this->logPageView($page['id']);
        
        // Capture UTMs
        $this->captureUtmParams($page['id']);
        
        // Render
        include VIEWS_PATH . '/public/landing-page.php';
    }
    
    private function logPageView(int $landingPageId): void {
        // Async logging - fire and forget
        // In production, use a queue or batch insert
        try {
            $db = Database::getInstance();
            // Simple daily counter - real implementation would be more sophisticated
        } catch (\Exception $e) {
            // Silently fail
        }
    }
    
    private function captureUtmParams(int $landingPageId): void {
        $utms = [];
        $fields = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];
        
        foreach ($fields as $field) {
            if (!empty($_GET[$field])) {
                $utms[$field] = $_GET[$field];
            }
        }
        
        if (!empty($utms)) {
            // Store in session for form submission
            $_SESSION['landing_utms'] = $utms;
            $_SESSION['landing_page_id'] = $landingPageId;
        }
    }
}
