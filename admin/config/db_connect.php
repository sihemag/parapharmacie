<?php
$conn = mysqli_connect('localhost', "root", "", 'parapharmaciemcs');
if (!$conn) {
    echo 'Connection Erreur: ' . mysqli_connect_error();
}
?>