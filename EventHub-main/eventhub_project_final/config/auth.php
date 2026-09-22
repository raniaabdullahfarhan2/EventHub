<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

function login_user($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        return false;
    }
    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_role'] = $user['user_role'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];
    return true;
}

function logout_user() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
}

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function current_user_role() {
    return $_SESSION['user_role'] ?? null;
}

function require_login() {
    if (!current_user_id()) {
        header("Location: login.php");
        exit;
    }
}

function require_role(array $roles) {
    require_login();
    $role = current_user_role();
    if (!$role || !in_array($role, $roles)) {
        http_response_code(403);
        echo "<h1 style='text-align:center;margin-top:80px;font-family:Segoe UI;'>Access denied</h1>";
        exit;
    }
}
?>
