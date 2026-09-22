<?php
require_once __DIR__ . '/config/auth.php';
global $pdo;
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone_number'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['user_role'] ?? 'Enthusiast';
    if ($name && $email && $password) {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $error = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $insert = $pdo->prepare("INSERT INTO users (full_name, email, phone_number, password_hash, user_role, account_status, created_at) VALUES (:full_name, :email, :phone_number, :password_hash, :role, 'Active', NOW())");
            $insert->execute([
                ':full_name' => $name,
                ':email' => $email,
                ':phone_number' => $phone,
                ':password_hash' => $hash,
                ':role' => $role
            ]);
            $success = 'Account created. You can log in now.';
        }
    } else {
        $error = 'Please fill all required fields.';
    }
}
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h4 class="text-center page-title mb-3">Sign Up</h4>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Type</label>
                        <select name="user_role" class="form-control">
                            <option value="Enthusiast">Enthusiast (Buyer)</option>
                            <option value="Event Organizer">Event Organizer</option>
                        </select>
                    </div>
                    <button class="btn-main w-100" type="submit">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
