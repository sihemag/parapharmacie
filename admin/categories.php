
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
            // Fetch categories from the database
            $categoryQuery = "SELECT * FROM categorie";
            $categoryResult = mysqli_query($conn, $categoryQuery);

            if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                while ($row = mysqli_fetch_assoc($categoryResult)) {
            ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['Category_Name']; ?></h5>
                                <a href="edit_category.php?id=<?php echo $row['Id_Category']; ?>" class="btn btn-primary">modifier</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No categories found</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include_once('include/footer.php'); ?>
