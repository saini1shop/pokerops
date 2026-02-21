<?php

namespace App\Controllers;

use App\Database;
use PDO;

class CampaignController
{
    public function index(): void
    {
        $db = Database::getInstance();

        // Fetch campaigns with stats
        $stmt = $db->query("
            SELECT c.*,
                   COUNT(lp.id) as landing_pages_count
            FROM igp_campaigns c
            LEFT JOIN igp_landing_pages lp ON c.id = lp.campaign_id
            GROUP BY c.id
            ORDER BY c.created_at DESC
        ");
        $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $user = [
            'id' => $_SESSION['admin_user_id'],
            'name' => $_SESSION['admin_user_name'],
            'role' => $_SESSION['admin_user_role'],
        ];

        include VIEWS_PATH . '/admin/campaigns/index.php';
    }

    public function create(): void
    {
        $user = [
            'id' => $_SESSION['admin_user_id'],
            'name' => $_SESSION['admin_user_name'],
            'role' => $_SESSION['admin_user_role'],
        ];

        include VIEWS_PATH . '/admin/campaigns/create.php';
    }

    public function store(): void
    {
        // Validate required fields
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'active';

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Campaign name is required';
        }

        if (!in_array($status, ['active', 'inactive', 'archived'])) {
            $errors[] = 'Invalid status selected';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: /admin/campaigns/new');
            exit;
        }

        $db = Database::getInstance();

        // Insert new campaign
        $stmt = $db->prepare("
            INSERT INTO igp_campaigns
            (name, description, status, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([$name, $description, $status]);

        $campaignId = $db->lastInsertId();

        $_SESSION['success_message'] = 'Campaign created successfully!';
        header('Location: /admin/campaigns/edit/' . $campaignId);
        exit;
    }

    public function edit(int $id): void
    {
        $db = Database::getInstance();

        // Fetch campaign
        $stmt = $db->prepare("SELECT * FROM igp_campaigns WHERE id = ?");
        $stmt->execute([$id]);
        $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$campaign) {
            http_response_code(404);
            include VIEWS_PATH . '/admin/error.php';
            return;
        }

        // Fetch related landing pages
        $stmt = $db->prepare("
            SELECT id, title, slug, status
            FROM igp_landing_pages
            WHERE campaign_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$id]);
        $landingPages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $user = [
            'id' => $_SESSION['admin_user_id'],
            'name' => $_SESSION['admin_user_name'],
            'role' => $_SESSION['admin_user_role'],
        ];

        include VIEWS_PATH . '/admin/campaigns/edit.php';
    }

    public function update(int $id): void
    {
        $db = Database::getInstance();

        // Verify campaign exists
        $stmt = $db->prepare("SELECT id FROM igp_campaigns WHERE id = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Campaign not found']);
            return;
        }

        // Get form data
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'active';

        // Validate required fields
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Campaign name is required';
        }

        if (!in_array($status, ['active', 'inactive', 'archived'])) {
            $errors[] = 'Invalid status selected';
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: /admin/campaigns/edit/' . $id);
            exit;
        }

        // Update campaign
        $stmt = $db->prepare("
            UPDATE igp_campaigns
            SET name = ?, description = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ");

        $stmt->execute([$name, $description, $status, $id]);

        $_SESSION['success_message'] = 'Campaign updated successfully!';
        header('Location: /admin/campaigns/edit/' . $id);
        exit;
    }

    public function delete(int $id): void
    {
        $db = Database::getInstance();

        // Check if campaign has landing pages
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM igp_landing_pages WHERE campaign_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            $_SESSION['error_message'] = 'Cannot delete campaign that has landing pages. Please remove all landing pages first.';
            header('Location: /admin/campaigns');
            exit;
        }

        // Delete campaign
        $stmt = $db->prepare("DELETE FROM igp_campaigns WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = 'Campaign deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Campaign not found';
        }

        header('Location: /admin/campaigns');
        exit;
    }

    public function toggleStatus(int $id): void
    {
        $db = Database::getInstance();

        // Toggle status between active and inactive
        $stmt = $db->prepare("
            UPDATE igp_campaigns
            SET status = CASE
                WHEN status = 'active' THEN 'inactive'
                ELSE 'active'
            END,
            updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = 'Campaign status updated successfully!';
        } else {
            $_SESSION['error_message'] = 'Campaign not found';
        }

        header('Location: /admin/campaigns');
        exit;
    }
}
