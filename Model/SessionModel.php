<?php
class SessionModel
{
    private $timeout_duration = 10; // 10 seconds

    public function __construct()
    {
        ini_set('session.cookie_lifetime', 0);
        session_start();
        $this->checkSessionTimeout();
    }

    private function checkSessionTimeout()
    {
        if (isset($_SESSION['LAST_ACTIVITY'])) {
            $elapsed = time() - $_SESSION['LAST_ACTIVITY'];
            if ($elapsed > $this->timeout_duration) {
                // Clear session
                $_SESSION = [];
                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000,
                        $params['path'], $params['domain'],
                        $params['secure'], $params['httponly']
                    );
                }
                session_destroy();
                session_start();
            }
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['role']);
    }

    public function getUserRole(): ?string
    {
        return $_SESSION['role'] ?? null;
    }

    public function getRoleMap(): array
    {
        return [
            "Staff" => "IRMS_Staff_job.php",
            "Deliveryman" => "IRMS_Peon_job.php",
            "Procurement" => "IRMS_Procurement_job.php",
            "Manager" => "IRMS_Manager_job.php",
            "Admin" => "IRMS_Admin_Product_dashboard.php"
        ];
    }
}
