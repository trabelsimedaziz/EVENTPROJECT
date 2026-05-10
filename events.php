<?php
require_once "header.php";
require_once "session.php";
require_once "models.php";
Verify_session();

$eventModel = new Event();
$events = $eventModel->listEvents();
?>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>Events</h2>
    <?php if($_SESSION['role'] == 'admin'): ?>
        <a href="manage_create.php?type=event" class="btn btn-success">Create New Event</a>
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
                <a href="manage_create.php?type=booking&event_id=<?php echo $e['id']; ?>" class="btn btn-primary">Book Now</a>
            <?php endif; ?>
            
            <?php if($_SESSION['role'] == 'admin'): ?>
                <a href="manage_event.php?action=edit&id=<?php echo $e['id']; ?>" class="btn btn-primary">Edit</a>
                <a href="manage_event.php?action=delete&id=<?php echo $e['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this event?')">Delete</a>
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
        <form action="request_handler.php" method="POST">
            <label>Event Name:</label>
            <input type="text" name="event_name" required>
            <label>Event Date:</label>
            <input type="date" name="event_date" required>
            <button type="submit" name="request_btn" class="btn btn-primary">Submit Request</button>
            <button type="button" onclick="document.getElementById('requestModal').style.display='none'" class="btn btn-danger">Cancel</button>
        </form>
    </div>
<?php endif; ?>

</div>
</body>
</html>
