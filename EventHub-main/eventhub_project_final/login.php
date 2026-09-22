<?php
require_once __DIR__ . '/config/auth.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (login_user($email, $password)) {
        $lower = strtolower($email);
        if (substr($lower, -14) === '@eventhuba.com') {
            $_SESSION['user_role'] = 'Admin';
        } elseif (substr($lower, -14) === '@eventhubs.com') {
            $_SESSION['user_role'] = 'Support Agent';
        }
        header("Location: index.php");
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card p-4">
                <h4 class="text-center page-title mb-3">Log In</h4>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn-main w-100" type="submit">Log In</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="forgot_password.php" class="link-purple">Forgot password?</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
