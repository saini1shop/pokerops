<?php
namespace App;

class AdminRouter {
    private array $routes = [];
    
    public function __construct() {
        $this->registerRoutes();
    }
    
    private function registerRoutes(): void {
        // Public admin routes (no auth required)
        $this->routes['GET']['login'] = ['AuthController', 'showLogin'];
        $this->routes['POST']['login/send-otp'] = ['AuthController', 'sendOtp'];
        $this->routes['POST']['login/verify'] = ['AuthController', 'verifyOtp'];
        
        // Protected routes (auth required)
        $this->routes['GET'][''] = ['DashboardController', 'index']; // /admin/
        $this->routes['GET']['dashboard'] = ['DashboardController', 'index'];
        $this->routes['GET']['logout'] = ['AuthController', 'logout'];
        
        // Landing pages management
        $this->routes['GET']['landing-pages'] = ['LandingPageController', 'index'];
        $this->routes['GET']['landing-pages/new'] = ['LandingPageController', 'create'];
        $this->routes['POST']['landing-pages'] = ['LandingPageController', 'store'];
        $this->routes['GET']['landing-pages/edit/(\d+)'] = ['LandingPageController', 'edit'];
        $this->routes['POST']['landing-pages/(\d+)'] = ['LandingPageController', 'update'];
        $this->routes['POST']['landing-pages/(\d+)/publish'] = ['LandingPageController', 'publish'];
        
        // Players
        $this->routes['GET']['players'] = ['PlayerController', 'index'];
        $this->routes['GET']['players/(\d+)'] = ['PlayerController', 'show'];
        
        // Campaigns
        $this->routes['GET']['campaigns'] = ['CampaignController', 'index'];
        
        // Venues
        $this->routes['GET']['venues'] = ['VenueController', 'index'];
        
        // Communities
        $this->routes['GET']['communities'] = ['CommunityController', 'index'];
        
        // Tournaments
        $this->routes['GET']['tournaments'] = ['TournamentController', 'index'];
        
        // Check-ins
        $this->routes['GET']['checkins'] = ['CheckinController', 'index'];
        
        // WhatsApp
        $this->routes['GET']['whatsapp'] = ['WhatsappController', 'compose'];
        $this->routes['GET']['whatsapp/compose'] = ['WhatsappController', 'compose'];
        $this->routes['POST']['whatsapp/send'] = ['WhatsappController', 'send'];
        $this->routes['POST']['whatsapp/send-bulk'] = ['WhatsappController', 'sendBulk'];
        $this->routes['GET']['whatsapp/logs'] = ['WhatsappController', 'logs'];
        $this->routes['GET']['whatsapp/search-players'] = ['WhatsappController', 'searchPlayers'];
        
        // Landing page preview
        $this->routes['GET']['landing-pages/preview/(\d+)'] = ['LandingPageController', 'preview'];
        
        // API routes
        $this->routes['GET']['api/stats'] = ['ApiController', 'stats'];
    }
    
    public function route(string $path): void {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Check if route exists
        $handler = $this->matchRoute($method, $path);
        
        if (!$handler) {
            http_response_code(404);
            $this->renderError('Page not found');
            return;
        }
        
        [$controllerClass, $action] = $handler;
        
        // Check auth for protected routes
        if (!$this->isPublicRoute($path) && !$this->isAuthenticated()) {
            $this->redirect('/admin/login');
            return;
        }
        
        // Load controller and execute action
        $controllerFile = INCLUDES_PATH . '/Controllers/' . $controllerClass . '.php';
        if (!file_exists($controllerFile)) {
            http_response_code(500);
            $this->renderError('Controller not found');
            return;
        }
        
        require_once $controllerFile;
        $fullClass = 'App\\Controllers\\' . $controllerClass;
        $controller = new $fullClass();
        
        // Extract route parameters if any
        $params = $this->extractParams($method, $path);
        
        if ($params) {
            $controller->$action(...$params);
        } else {
            $controller->$action();
        }
    }
    
    private function matchRoute(string $method, string $path): ?array {
        if (!isset($this->routes[$method])) {
            return null;
        }
        
        // Direct match
        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }
        
        // Pattern match for routes with parameters
        foreach ($this->routes[$method] as $route => $handler) {
            if (strpos($route, '(') !== false) {
                $pattern = '#^' . $route . '$#';
                if (preg_match($pattern, $path)) {
                    return $handler;
                }
            }
        }
        
        return null;
    }
    
    private function extractParams(string $method, string $path): array {
        $params = [];
        
        foreach ($this->routes[$method] as $route => $handler) {
            if (strpos($route, '(') !== false) {
                $pattern = '#^' . $route . '$#';
                if (preg_match($pattern, $path, $matches)) {
                    array_shift($matches); // Remove full match
                    return $matches;
                }
            }
        }
        
        return $params;
    }
    
    private function isPublicRoute(string $path): bool {
        return str_starts_with($path, 'login') || str_starts_with($path, 'api/');
    }
    
    private function isAuthenticated(): bool {
        return isset($_SESSION['admin_user_id']) && $_SESSION['admin_user_id'] > 0;
    }
    
    private function redirect(string $url): void {
        header("Location: {$url}");
        exit;
    }
    
    private function renderError(string $message): void {
        http_response_code(404);
        include VIEWS_PATH . '/admin/error.php';
    }
}
