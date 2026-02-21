<?php
namespace App\Controllers;

class PlayerController {
    public function index(): void {
        echo "Players List - Coming Soon";
    }
    
    public function show(int $id): void {
        echo "Player Details {$id} - Coming Soon";
    }
}

class CampaignController {
    public function index(): void {
        echo "Campaigns List - Coming Soon";
    }
}

class VenueController {
    public function index(): void {
        echo "Venues List - Coming Soon";
    }
}

class CommunityController {
    public function index(): void {
        echo "Communities List - Coming Soon";
    }
}

class TournamentController {
    public function index(): void {
        echo "Tournaments List - Coming Soon";
    }
}

class CheckinController {
    public function index(): void {
        echo "Check-ins List - Coming Soon";
    }
}

class ApiController {
    public function stats(): void {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'API stats - Coming Soon']);
    }
}
