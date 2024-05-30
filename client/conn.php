<?php
// Replace 'xxpp3' with your MySQL username and '<passwd>' with your password
$conn = mysqli_connect('localhost', 'root', '', 'parapharmaciemcs');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>