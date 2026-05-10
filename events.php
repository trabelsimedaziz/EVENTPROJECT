<?php
require_once "header.php";
require_once "session.php";
require_once "models.php";
Verify_session();

$action = $_GET['action'] ?? 'list';

// delete event
if($action == 'delete') {
    Verify_admin();
    $id = $_GET['id'];
    $eventModel = new Event();
    $eventModel->deleteEvent($id);
    header('location:events.php');
    exit;
}

// update event
if(isset($_POST['update_btn'])) {
    Verify_admin();
    $id = $_GET['id'];
    $eventModel = new Event();
    $eventModel->title = $_POST['title'];
    $eventModel->description = $_POST['description'];
    $eventModel->location = $_POST['location'];
    $eventModel->event_date = $_POST['event_date'];
    $eventModel->total_seats = $_POST['total_seats'];
    $eventModel->updateEvent($id);
    header('location:events.php');
    exit;
}

// create event
if(isset($_POST['create_event_btn'])) {
    Verify_admin();
    $evt = new Event();
    $evt->title = $_POST['title'];
    $evt->description = $_POST['description'];
    $evt->location = $_POST['location'];
    $evt->event_date = $_POST['event_date'];
    $evt->total_seats = $_POST['total_seats'];
    $evt->insertEvent();
    header('location:events.php');
    exit;
}

// book event
if(isset($_POST['book_btn'])) {
    $eventModel = new Event();
    $eid = $_POST['event_id'];
    $seats = $_POST['seats'];
    $evt = $eventModel->getEvent($eid)->fetch(PDO::FETCH_ASSOC);
    if($evt && $evt['available_seats'] >= $seats) {
        $booking = new Booking();
        $booking->user_id = $_SESSION['user_id'];
        $booking->event_id = $eid;
        $booking->customer_name = $_POST['customer_name'];
        $booking->seats_booked = $seats;
        $booking->insertBooking();
        $eventModel->updateSeats($eid, $seats);
        header('location:booking.php');
        exit;
    } else {
        echo "<p style='color:red'>Error: Not enough seats available!</p>";
    }
}

// show edit form
if($action == 'edit') {
    Verify_admin();
    $id = $_GET['id'];
    $eventModel = new Event();
    $e = $eventModel->getEvent($id)->fetch(PDO::FETCH_ASSOC);
?>
    <h2>Edit Event</h2>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo $e['title']; ?>" required>
        <label>Description:</label>
        <textarea name="description" required><?php echo $e['description']; ?></textarea>
        <label>Location:</label>
        <input type="text" name="location" value="<?php echo $e['location']; ?>" required>
        <label>Date:</label>
        <input type="date" name="event_date" value="<?php echo $e['event_date']; ?>" required>
        <label>Total Seats:</label>
        <input type="number" name="total_seats" value="<?php echo $e['total_seats']; ?>" required>
        <button type="submit" name="update_btn" class="btn btn-primary">Update Event</button>
    </form>

<?php } elseif($action == 'create') {
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

<?php } elseif($action == 'book') {
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

<?php } else { // list all events
    $eventModel = new Event();
    $events = $eventModel->listEvents();
?>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Events</h2>
        <?php if($_SESSION['role'] == 'admin'): ?>
            <a href="events.php?action=create" class="btn btn-success">Create New Event</a>
        <?php endif; ?>
    </div>

    <div class="card-grid">
        <?php while($e = $events->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="card">
                <h4><?php echo $e['title']; ?></h4>
                <p><?php echo $e['description']; ?></p>
                <p><strong>Location:</strong> <?php echo $e['location']; ?></p>
                <p><strong>Date:</strong> <?php echo $e['event_date']; ?></p>
                <p><strong>Seats:</strong> <?php echo $e['available_seats']; ?> / <?php echo $e['total_seats']; ?></p>

                <?php
                    $seats = $e['available_seats'];
                    $class = "badge-available"; $text = "Available";
                    if($seats <= 0) { $class = "badge-soldout"; $text = "Sold Out"; }
                    elseif($seats < 10) { $class = "badge-limited"; $text = "Limited"; }
                ?>
                <span class="badge <?php echo $class; ?>"><?php echo $text; ?></span>
                <br><br>

                <?php if($_SESSION['role'] == 'user' && $seats > 0): ?>
                    <a href="events.php?action=book&event_id=<?php echo $e['id']; ?>" class="btn btn-primary">Book Now</a>
                <?php endif; ?>

                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="events.php?action=edit&id=<?php echo $e['id']; ?>" class="btn btn-primary">Edit</a>
                    <a href="events.php?action=delete&id=<?php echo $e['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this event?')">Delete</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <?php if($_SESSION['role'] == 'user'): ?>
        <hr>
        <h3>Request a New Event</h3>
        <button onclick="document.getElementById('requestModal').style.display='block'" class="btn btn-success">Request Event</button>

        <div id="requestModal" class="modal" style="display:none;">
            <h3>Request Event</h3>
            <form action="request.php" method="POST">
                <label>Event Name:</label>
                <input type="text" name="event_name" required>
                <label>Event Date:</label>
                <input type="date" name="event_date" required>
                <button type="submit" name="request_btn" class="btn btn-primary">Submit Request</button>
                <button type="button" onclick="document.getElementById('requestModal').style.display='none'" class="btn btn-danger">Cancel</button>
            </form>
        </div>
    <?php endif; ?>

<?php } ?>

</div>
</body>
</html>
