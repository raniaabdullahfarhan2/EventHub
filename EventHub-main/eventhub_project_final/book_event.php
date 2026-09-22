<?php
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/EventBooking.php';
require_login();
$userId = current_user_id();
$eventId = (int)($_POST['event_id'] ?? 0);
$quantity = (int)($_POST['ticket_quantity'] ?? 1);
$paymentMethod = 'Online';
$booking = new EventBooking($pdo);
$result = $booking->bookTickets($userId, $eventId, $quantity, $paymentMethod);
if ($result['success']) {
    header("Location: my_tickets.php?booking_id=" . $result['booking_id']);
    exit;
}
echo "Booking failed: " . htmlspecialchars($result['message']);
