<?php
require_once __DIR__ . '/config/auth.php';
require_login();
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$userId = current_user_id();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    if ($subject && $desc) {
        $stmt = $pdo->prepare("INSERT INTO support_issues (support_user_id, subject, description, issue_status, priority, created_at) VALUES (:uid, :subject, :description, 'Open', 'Medium', NOW())");
        $stmt->execute([':uid' => $userId, ':subject' => $subject, ':description' => $desc]);
    }
}
$stmt = $pdo->prepare("SELECT * FROM support_issues WHERE support_user_id = :uid ORDER BY created_at DESC");
$stmt->execute([':uid' => $userId]);
$issues = $stmt->fetchAll();
?>
<div class="container mt-4">
    <h3 class="mb-3 page-title">My Support Issues</h3>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Create New Issue</h5>
            <form method="post">
                <div class="mb-2">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <button class="btn-main mt-2" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <table class="table table-bordered table-purple">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Created</th>
                <th>Resolved</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($issues as $i): ?>
                <tr>
                    <td><?= htmlspecialchars($i['subject']) ?></td>
                    <td><?= htmlspecialchars($i['issue_status']) ?></td>
                    <td><?= htmlspecialchars($i['priority']) ?></td>
                    <td><?= htmlspecialchars($i['created_at']) ?></td>
                    <td><?= htmlspecialchars($i['resolved_at'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$issues): ?>
                <tr><td colspan="5">No issues yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
