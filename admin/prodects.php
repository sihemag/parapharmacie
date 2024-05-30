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
            // Fetch products from the database
            $productQuery = "SELECT * FROM medicament";
            $productResult = mysqli_query($conn, $productQuery);

            if ($productResult && mysqli_num_rows($productResult) > 0) {
                while ($row = mysqli_fetch_assoc($productResult)) {
            ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100"> <!-- Added fixed height class -->
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Image_Med']); ?>" class="card-img-top img-fluid h-100" alt="Product Image"> <!-- Added img-fluid class -->
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['Name_Med']; ?></h5>
                                <a href="modify.php?id=<?php echo $row['Id_Medicament']; ?>" class="btn btn-primary">Modifier</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No products found</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include_once('include/footer.php'); ?>
