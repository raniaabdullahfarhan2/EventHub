<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Support']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issueId = (int)($_POST['issue_id'] ?? 0);
    $status = $_POST['issue_status'] ?? '';
    if ($issueId && $status) {
        $stmt = $pdo->prepare("UPDATE support_issues SET issue_status = :status WHERE issue_id = :id");
        $stmt->execute([':status' => $status, ':id' => $issueId]);
    }
}
$issues = $pdo->query("SELECT i.*, u.full_name AS requester_name FROM support_issues i LEFT JOIN users u ON i.support_user_id = u.user_id ORDER BY i.created_at DESC")->fetchAll();
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">All Issues</h3>
    <table class="table table-bordered table-purple">
        <thead>
            <tr>
                <th>#</th><th>User</th><th>Subject</th><th>Status</th><th>Priority</th><th>Created</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($issues as $i): ?>
                <tr>
                    <td><?= (int)$i['issue_id'] ?></td>
                    <td><?= htmlspecialchars($i['requester_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($i['subject']) ?></td>
                    <td><?= htmlspecialchars($i['issue_status']) ?></td>
                    <td><?= htmlspecialchars($i['priority']) ?></td>
                    <td><?= htmlspecialchars($i['created_at']) ?></td>
                    <td>
                        <form method="post" class="d-flex gap-1">
                            <input type="hidden" name="issue_id" value="<?= (int)$i['issue_id'] ?>">
                            <select name="issue_status" class="form-select form-select-sm">
                                <option value="Open">Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Closed">Closed</option>
                            </select>
                            <button class="btn-main-outline btn-sm" type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
