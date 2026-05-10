<?php
class Connection {
    function CNXbase() {
        try {
            // Using same dbname as Lab 3: iitdata
            $dbc = new PDO('mysql:host=localhost;dbname=iitdata', 'root', '');
            return $dbc;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
?>
