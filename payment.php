<?php
require_once "header.php";
require_once "session.php";
require_once "models.php";
Verify_session();

$request_id = $_GET['request_id'];
$requestModel = new Request();
$reqData = $requestModel->getRequest($request_id)->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['pay_btn'])) {
    $evt = new Event();
    $evt->title = $reqData['event_name'];
    $evt->description = "User requested event";
    $evt->location = "TBD";
    $evt->event_date = $reqData['event_date'];
    $evt->total_seats = 50;
    $evt->insertEvent();
    
    $requestModel->updateStatus($request_id, 'paid');
    header('location:events.php');
}

if (isset($_POST['cancel_btn'])) {
    $requestModel->updateStatus($request_id, 'canceled');
    header('location:bookings.php');
}
?>

<div class="modal">
    <h3>Payment for: <?php echo $reqData['event_name']; ?></h3>
    <p><strong>Price:</strong> $<?php echo $reqData['price']; ?></p>
    <p><strong>Date:</strong> <?php echo $reqData['event_date']; ?></p>
    
    <form method="POST">
        <label>Credit Card Number:</label>
        <input type="text" placeholder="XXXX-XXXX-XXXX-XXXX" required>
        <label>Expiry:</label>
        <input type="text" placeholder="MM/YY" required style="width: 100px;">
        <label>CVV:</label>
        <input type="text" placeholder="123" required style="width: 60px;">
        
        <div style="margin-top: 10px;">
            <button type="submit" name="pay_btn" class="btn btn-success">Pay Now</button>
            <button type="submit" name="cancel_btn" class="btn btn-danger">Cancel</button>
        </div>
    </form>
</div>

</div>
</body>
</html>
