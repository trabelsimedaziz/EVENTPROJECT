<?php
require_once "header.php";
require_once "models.php";
$eventModel = new Event();
$events = $eventModel->listEvents();
$allEvents = $events->fetchAll(PDO::FETCH_ASSOC);
$totalCount = count($allEvents);
$upcomingCount = 0;
foreach($allEvents as $e) {
    if(strtotime($e['event_date']) >= time()) $upcomingCount++;
}
?>

<div class="hero">
    <h1>Welcome to EventSystem</h1>
    <p>Discover and book amazing events happening near you.</p>
    <a href="events.php" class="btn btn-primary">Browse Events</a>
</div>

<h3>Live Stats</h3>
<p>Total Events: <?php echo $totalCount; ?> | Upcoming Events: <?php echo $upcomingCount; ?></p>

<h3>Upcoming Events Preview</h3>
<div class="card-grid">
    <?php foreach(array_slice($allEvents, 0, 3) as $e): ?>
        <div class="card">
            <h4><?php echo $e['title']; ?></h4>
            <p><?php echo $e['event_date']; ?> | <?php echo $e['location']; ?></p>
            <?php 
                $seats = $e['available_seats'];
                $class = "badge-available";
                $text = "Available";
                if($seats <= 0) { $class = "badge-soldout"; $text = "Sold Out"; }
                elseif($seats < 10) { $class = "badge-limited"; $text = "Limited ($seats left)"; }
            ?>
            <span class="badge <?php echo $class; ?>"><?php echo $text; ?></span>
        </div>
    <?php endforeach; ?>
</div>

</div>
</body>
</html>
