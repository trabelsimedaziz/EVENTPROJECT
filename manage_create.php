<?php
require_once "header.php";
require_once "session.php";
require_once "models.php";
Verify_session();

$type = $_GET['type'] ?? 'booking';

// Handle Event Creation (Admin Only)
if (isset($_POST['create_event_btn'])) {
    Verify_admin();
    $evt = new Event();
    $evt->title = $_POST['title'];
    $evt->description = $_POST['description'];
    $evt->location = $_POST['location'];
    $evt->event_date = $_POST['event_date'];
    $evt->total_seats = $_POST['total_seats'];
    $evt->insertEvent();
    header('location:events.php');
}

// Handle Booking Creation
if (isset($_POST['book_btn'])) {
    $eventModel = new Event();
    $eid = $_POST['event_id'];
    $seats = $_POST['seats'];
    $evt = $eventModel->getEvent($eid)->fetch(PDO::FETCH_ASSOC);
    if ($evt && $evt['available_seats'] >= $seats) {
        $booking = new Booking();
        $booking->user_id = $_SESSION['user_id'];
        $booking->event_id = $eid;
        $booking->customer_name = $_POST['customer_name'];
        $booking->seats_booked = $seats;
        $booking->insertBooking();
        $eventModel->updateSeats($eid, $seats);
        header('location:bookings.php');
    } else {
        echo "<p style='color:red'>Error: Not enough seats available!</p>";
    }
}

if ($type == 'event'): 
    Verify_admin();
?>
    <h2>Create New Event</h2>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" required>
        <label>Description:</label>
        <textarea name="description" required></textarea>
        <label>Location:</label>
        <input type="text" name="location" required>
        <label>Date:</label>
        <input type="date" name="event_date" required>
        <label>Total Seats:</label>
        <input type="number" name="total_seats" required>
        <button type="submit" name="create_event_btn" class="btn btn-success">Create Event</button>
    </form>

<?php else: 
    $event_id = $_GET['event_id'] ?? '';
    $eventModel = new Event();
    $events = $eventModel->listEvents()->fetchAll(PDO::FETCH_ASSOC);
?>
    <h2>Book Event</h2>
    <form method="POST">
        <label>Select Event:</label>
        <select name="event_id" required>
            <?php foreach($events as $e): ?>
                <option value="<?php echo $e['id']; ?>" <?php if($e['id'] == $event_id) echo 'selected'; ?>>
                    <?php echo $e['title']; ?> (<?php echo $e['available_seats']; ?> left)
                </option>
            <?php endforeach; ?>
        </select>
        <label>Customer Name:</label>
        <input type="text" name="customer_name" required>
        <label>Number of Seats:</label>
        <input type="number" name="seats" min="1" value="1" required>
        <button type="submit" name="book_btn" class="btn btn-primary">Confirm Booking</button>
    </form>
<?php endif; ?>

</div>
</body>
</html>
