<?php
require_once "header.php";
require_once "session.php";
require_once "models.php";
Verify_session();
Verify_admin();

$action = $_GET['action'] ?? 'edit';
$id = $_GET['id'] ?? null;

if (!$id) {
    header('location:events.php');
    exit();
}

$eventModel = new Event();

if ($action == 'delete') {
    $eventModel->deleteEvent($id);
    header('location:events.php');
    exit();
}

if (isset($_POST['update_btn'])) {
    $eventModel->title = $_POST['title'];
    $eventModel->description = $_POST['description'];
    $eventModel->location = $_POST['location'];
    $eventModel->event_date = $_POST['event_date'];
    $eventModel->total_seats = $_POST['total_seats'];
    $eventModel->updateEvent($id);
    header('location:events.php');
    exit();
}

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

</div>
</body>
</html>
