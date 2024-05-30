<?php
ob_start();
include("../conn.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch data from the marques table
$sqlMarques = "SELECT * FROM marques";
$resultMarques = mysqli_query($conn, $sqlMarques);

// Check if there are any brands
if (mysqli_num_rows($resultMarques) > 0) {
    $marques = mysqli_fetch_all($resultMarques, MYSQLI_ASSOC);
} else {
    $noMarquesMessage = "No brands available.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include('Medicaments.css') ?></style>
    <title>Marques</title>
</head>
<body>
    <?php include("../header/header.php"); ?>
    <div class="Margin-Left">
        <h1>Marques</h1>
        <img class="imageSubNavBar" src="../Image/MedicamentNoBackGround.png" />
    </div>
    <div class="Container">
        <div class="Right-Container">
            <?php if (isset($marques)): ?>
                <?php foreach ($marques as $marque): ?>
                    <div class="MarqueBox">
                        <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($marque['img']); ?>" />
                        <h3><?php echo $marque['marques_name']; ?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?php echo $noMarquesMessage; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php include("../footer/footer.php"); ?> 
    <script><?php include("Medicaments.js");?></script>
</body>
</html>
<?php
ob_end_flush();
?>
