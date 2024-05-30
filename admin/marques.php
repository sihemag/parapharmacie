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
            // Fetch marques from the database
            $marquesQuery = "SELECT * FROM marques";
            $marquesResult = mysqli_query($conn, $marquesQuery);

            if ($marquesResult && mysqli_num_rows($marquesResult) > 0) {
                while ($row = mysqli_fetch_assoc($marquesResult)) {
            ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['marques_name']; ?></h5>
                                <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['img']); ?>" class="card-img-top" alt="<?php echo $row['marques_name']; ?>">
                                <!-- You can add more details about the marques here if needed -->
                                <!-- Example: <p class="card-text">Description: <?php echo $row['description']; ?></p> -->
                                <a href="edit_marques.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Modifier</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No marques found</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include_once('include/footer.php'); ?>
