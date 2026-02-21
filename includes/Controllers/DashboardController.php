<?php
namespace App\Controllers;

use App\Database;

class DashboardController {
    
    public function index(): void {
        $db = Database::getInstance();
        
        // Get stats for dashboard
        $stats = [
            'total_players' => $this->getCount($db, 'igp_players'),
            'players_today' => $this->getPlayersToday($db),
            'signups_today' => $this->getSignupsToday($db),
            'active_checkins' => $this->getActiveCheckins($db),
            'whatsapp_sent_today' => $this->getWhatsappSentToday($db),
            'upcoming_tournaments' => $this->getUpcomingTournamentsCount($db),
        ];
        
        // Get recent activity
        $recentSignups = $this->getRecentSignups($db, 5);
        $recentCheckins = $this->getRecentCheckins($db, 5);
        
        // Get campaigns summary
        $campaigns = $this->getCampaignsSummary($db);
        
        $user = [
            'id' => $_SESSION['admin_user_id'],
            'name' => $_SESSION['admin_user_name'],
            'role' => $_SESSION['admin_user_role'],
        ];
        
        include VIEWS_PATH . '/admin/dashboard/index.php';
    }
    
    private function getCount($db, string $table): int {
        $stmt = $db->query("SELECT COUNT(*) FROM {$table}");
        return (int) $stmt->fetchColumn();
    }
    
    private function getPlayersToday($db): int {
        $stmt = $db->prepare("SELECT COUNT(*) FROM igp_players WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    private function getSignupsToday($db): int {
        $stmt = $db->prepare("SELECT COUNT(*) FROM igp_player_signups WHERE DATE(submitted_at) = CURDATE()");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    private function getActiveCheckins($db): int {
        $stmt = $db->prepare("SELECT COUNT(*) FROM igp_player_checkins WHERE status = 'checked_in'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    private function getWhatsappSentToday($db): int {
        $stmt = $db->prepare("SELECT COUNT(*) FROM igp_whatsapp_logs 
            WHERE DATE(created_at) = CURDATE() AND direction = 'outbound' AND status IN ('sent', 'delivered', 'read')");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    private function getUpcomingTournamentsCount($db): int {
        $stmt = $db->prepare("SELECT COUNT(*) FROM igp_tournaments 
            WHERE status = 'scheduled' AND start_time > NOW()");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    private function getRecentSignups($db, int $limit): array {
        $stmt = $db->prepare("SELECT ps.*, p.name as player_name, lp.title as landing_page 
            FROM igp_player_signups ps 
            LEFT JOIN igp_players p ON ps.player_id = p.id 
            LEFT JOIN igp_landing_pages lp ON ps.landing_page_id = lp.id 
            ORDER BY ps.submitted_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    private function getRecentCheckins($db, int $limit): array {
        $stmt = $db->prepare("SELECT pc.*, p.name as player_name, v.name as venue_name 
            FROM igp_player_checkins pc 
            JOIN igp_players p ON pc.player_id = p.id 
            JOIN igp_venues v ON pc.venue_id = v.id 
            ORDER BY pc.checkin_time DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    private function getCampaignsSummary($db): array {
        $stmt = $db->query("SELECT c.*, lp.title as landing_page, COUNT(ps.id) as signup_count 
            FROM igp_campaigns c 
            LEFT JOIN igp_landing_pages lp ON c.landing_page_id = lp.id 
            LEFT JOIN igp_player_signups ps ON ps.campaign_id = c.id 
            WHERE c.status = 'active' 
            GROUP BY c.id 
            ORDER BY c.created_at DESC 
            LIMIT 5");
        return $stmt->fetchAll();
    }
}
