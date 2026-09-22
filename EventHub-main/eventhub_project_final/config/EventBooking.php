<?php
require_once __DIR__ . '/db.php';

class EventBooking
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function bookTickets(int $userId, int $eventId, int $quantity, string $paymentMethod = 'Online'): array
    {
        if ($quantity <= 0) {
            return ['success' => false, 'message' => 'Invalid ticket quantity'];
        }
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("SELECT event_id, event_name, ticket_price, capacity, tickets_sold, tickets_available FROM events WHERE event_id = :event_id FOR UPDATE");
            $stmt->execute([':event_id' => $eventId]);
            $event = $stmt->fetch();
            if (!$event) {
                $this->pdo->rollBack();
                return ['success' => false, 'message' => 'Event not found'];
            }
            if ((int)$event['tickets_available'] < $quantity) {
                $this->pdo->rollBack();
                return ['success' => false, 'message' => 'Not enough tickets available'];
            }
            $ticketPrice = (float)$event['ticket_price'];
            $totalPrice = $ticketPrice * $quantity;
            $bookingStmt = $this->pdo->prepare("INSERT INTO bookings (user_id, event_id, ticket_quantity, total_price, payment_method, payment_status, booking_date, booking_status) VALUES (:user_id, :event_id, :ticket_quantity, :total_price, :payment_method, :payment_status, NOW(), :booking_status)");
            $bookingStmt->execute([
                ':user_id' => $userId,
                ':event_id' => $eventId,
                ':ticket_quantity' => $quantity,
                ':total_price' => $totalPrice,
                ':payment_method' => $paymentMethod,
                ':payment_status' => 'Paid',
                ':booking_status' => 'Confirmed'
            ]);
            $bookingId = (int)$this->pdo->lastInsertId();
            $ticketStmt = $this->pdo->prepare("INSERT INTO tickets (booking_id, user_id, event_id, qr_code, ticket_status, checked_in, check_in_time, created_at) VALUES (:booking_id, :user_id, :event_id, :qr_code, :ticket_status, :checked_in, :check_in_time, NOW())");
            for ($i = 0; $i < $quantity; $i++) {
                $qrCode = hash('sha256', $bookingId . '-' . $userId . '-' . microtime(true) . '-' . $i);
                $ticketStmt->execute([
                    ':booking_id' => $bookingId,
                    ':user_id' => $userId,
                    ':event_id' => $eventId,
                    ':qr_code' => $qrCode,
                    ':ticket_status' => 'Active',
                    ':checked_in' => 0,
                    ':check_in_time' => null
                ]);
            }
            $updateEventStmt = $this->pdo->prepare("UPDATE events SET tickets_sold = tickets_sold + :qty, tickets_available = tickets_available - :qty WHERE event_id = :event_id");
            $updateEventStmt->execute([':qty' => $quantity, ':event_id' => $eventId]);
            $notifStmt = $this->pdo->prepare("INSERT INTO notifications (user_id, notification_type, title, message, related_event_id, is_read, sent_via, created_at) VALUES (:user_id, :notification_type, :title, :message, :related_event_id, 0, :sent_via, NOW())");
            $notifStmt->execute([
                ':user_id' => $userId,
                ':notification_type' => 'Booking Confirmation',
                ':title' => 'Booking Confirmed',
                ':message' => 'Your booking for event "' . $event['event_name'] . '" is confirmed.',
                ':related_event_id' => $eventId,
                ':sent_via' => 'Both'
            ]);
            $this->pdo->commit();
            return ['success' => true, 'message' => 'Booking completed successfully', 'booking_id' => $bookingId];
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
