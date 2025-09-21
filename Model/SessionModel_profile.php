<?php
// models/SessionModel.php
class SessionModel {
    // Start session with custom lifetime
    public static function startSession($lifetime = 300) {
        session_set_cookie_params($lifetime);
        session_start();
    }

    // Reset (destroy + restart) session
    public static function resetSession() {
        session_unset();
        session_destroy();
        session_start();
    }

    // Set "apply job" flag
    public static function applyJob() {
        $_SESSION['apply_job'] = true;
    }
}
