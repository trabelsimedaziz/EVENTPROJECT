<?php
require_once('pdo.php');

class Account {
    public $email;
    public $password;

    function getUser() {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "SELECT * FROM account WHERE email='$this->email' AND password='$this->password'";
        $res = $pdo->query($req) or print_r($pdo->errorInfo());
        return $res;
    }

    function register() {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "INSERT INTO account (email, password, role) VALUES ('$this->email', '$this->password', 'user')";
        $pdo->exec($req) or print_r($pdo->errorInfo());
    }
}

class Event {
    public $id;
    public $title;
    public $description;
    public $location;
    public $event_date;
    public $total_seats;
    public $available_seats;

    function listEvents() {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "SELECT * FROM event WHERE status='active' ORDER BY event_date ASC";
        return $pdo->query($req);
    }

    function getEvent($id) {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "SELECT * FROM event WHERE id = $id";
        return $pdo->query($req);
    }

    function insertEvent() {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "INSERT INTO event (title, description, location, event_date, total_seats, available_seats) 
                VALUES ('$this->title', '$this->description', '$this->location', '$this->event_date', $this->total_seats, $this->total_seats)";
        $pdo->exec($req);
    }

    function updateEvent($id) {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "UPDATE event SET title='$this->title', description='$this->description', location='$this->location', 
                event_date='$this->event_date', total_seats=$this->total_seats WHERE id=$id";
        $pdo->exec($req);
    }

    function deleteEvent($id) {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "DELETE FROM event WHERE id=$id";
        $pdo->exec($req);
    }
    
    function updateSeats($id, $seats) {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "UPDATE event SET available_seats = available_seats - $seats WHERE id=$id";
        $pdo->exec($req);
    }
}

class Booking {
    public $user_id;
    public $event_id;
    public $customer_name;
    public $seats_booked;

    function listBookings() {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "SELECT b.*, e.title as event_title FROM booking b JOIN event e ON b.event_id = e.id";
        return $pdo->query($req);
    }

    function insertBooking() {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "INSERT INTO booking (user_id, event_id, customer_name, seats_booked) 
                VALUES ($this->user_id, $this->event_id, '$this->customer_name', $this->seats_booked)";
        $pdo->exec($req);
    }
    
    function deleteBooking($id) {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "DELETE FROM booking WHERE id=$id";
        $pdo->exec($req);
    }
}

class Request {
    public $user_id;
    public $event_name;
    public $event_date;
    public $status;
    public $price;

    function listRequests() {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "SELECT r.*, a.email FROM request r JOIN account a ON r.user_id = a.id";
        return $pdo->query($req);
    }

    function insertRequest() {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "INSERT INTO request (user_id, event_name, event_date, status) 
                VALUES ($this->user_id, '$this->event_name', '$this->event_date', 'pending')";
        $pdo->exec($req);
    }

    function updateStatus($id, $status, $price = 0) {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "UPDATE request SET status='$status', price=$price WHERE id=$id";
        $pdo->exec($req);
    }
    
    function getRequest($id) {
        $cnx = new Connection();
        $pdo = $cnx->CNXbase();
        $req = "SELECT * FROM request WHERE id=$id";
        return $pdo->query($req);
    }
}
?>
