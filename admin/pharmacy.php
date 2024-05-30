<?php
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn'])) {
    exit(header("Location: ./signin.php"));
}

?>

<?php include_once('include/header.php'); ?>

<div class="content">
    <div class="container-fluid pt-4 px-4">
        <div class="row">
            <?php
            // Fetch pharmacies from the database
            $pharmacyQuery = "SELECT * FROM pharmacy";
            $pharmacyResult = mysqli_query($conn, $pharmacyQuery);

            if ($pharmacyResult && mysqli_num_rows($pharmacyResult) > 0) {
                while ($row = mysqli_fetch_assoc($pharmacyResult)) {
            ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                <p class="card-text">Dette: <?php echo $row['debt']; ?></p>
                                <a href="edit_pharmacy.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Modifier</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No pharmacies found</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include_once('include/footer.php'); ?>
