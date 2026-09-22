<?php
require_once __DIR__ . '/config/auth.php';
global $pdo;
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if ($email && $newPassword && $confirm) {
        if ($newPassword !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            if (!$user) {
                $error = 'User not found.';
            } else {
                $hash = password_hash($newPassword, PASSWORD_BCRYPT);
                $update = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE user_id = :id");
                $update->execute([':hash' => $hash, ':id' => $user['user_id']]);
                $success = 'Password updated. You can log in now.';
            }
        }
    } else {
        $error = 'Please fill all fields.';
    }
}
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h4 class="text-center page-title mb-3">Reset Password</h4>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button class="btn-main w-100" type="submit">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
