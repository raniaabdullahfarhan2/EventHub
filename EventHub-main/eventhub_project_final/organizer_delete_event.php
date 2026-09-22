<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Event Organizer']);
global $pdo;
$uid = current_user_id();
$eventId = (int)($_POST['event_id'] ?? 0);
if ($eventId) {
    $stmt = $pdo->prepare("DELETE FROM events WHERE event_id = :id AND organizer_id = :org");
    $stmt->execute([':id' => $eventId, ':org' => $uid]);
}
header("Location: organizer_events.php");
exit;
