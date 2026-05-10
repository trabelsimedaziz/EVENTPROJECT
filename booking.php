<?php
require_once "header.php";
require_once "session.php";
require_once "models.php";
Verify_session();

// handle delete
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $bookingModel = new Booking();
    $bookingModel->deleteBooking($id);
    header('location:booking.php');
    exit;
}

$bookingModel = new Booking();
$bookings = $bookingModel->listBookings();

$requestModel = new Request();
$requests = $requestModel->listRequests();
?>

<h2>All Bookings</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Event</th>
        <th>Customer Name</th>
        <th>Seats</th>
        <th>Actions</th>
    </tr>
    <?php while($b = $bookings->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $b['id']; ?></td>
            <td><?php echo $b['event_title']; ?></td>
            <td><?php echo $b['customer_name']; ?></td>
            <td><?php echo $b['seats_booked']; ?></td>
            <td>
                <?php if($_SESSION['role'] == 'admin' || $_SESSION['user_id'] == $b['user_id']): ?>
                    <a href="booking.php?delete=<?php echo $b['id']; ?>" class="btn btn-danger">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<hr>
<h2>Event Requests</h2>
<table>
    <tr>
        <th>Event Name</th>
        <th>Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while($r = $requests->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $r['event_name']; ?></td>
            <td><?php echo $r['event_date']; ?></td>
            <td>
                <?php if($r['status'] == 'pending'): ?>
                    <span style="color: green;">pending</span>
                <?php else: ?>
                    <?php echo $r['status']; ?>
                <?php endif; ?>
            </td>
            <td>
                <?php if($_SESSION['role'] == 'admin' && $r['status'] == 'pending'): ?>
                    <form action="request.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                        <input type="number" name="price" placeholder="Price" required style="width:80px;">
                        <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                        <button type="submit" name="action" value="decline" class="btn btn-danger">Decline</button>
                    </form>
                <?php elseif($_SESSION['role'] == 'user' && $r['status'] == 'approved' && $_SESSION['user_id'] == $r['user_id']): ?>
                    <a href="payment.php?request_id=<?php echo $r['id']; ?>" class="btn btn-primary">Pay & Confirm</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</div>
</body>
</html>
