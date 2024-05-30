<?php
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn'])) {
    exit(header("Location: ./signin.php"));
}

// Extract the category ID from the URL
if (isset($_GET['id'])) {
    $categoryId = $_GET['id'];
} else {
    exit("Category ID not provided.");
}

// Fetch category details from the database
$categoryQuery = "SELECT * FROM categorie WHERE Id_Category = $categoryId";
$categoryResult = mysqli_query($conn, $categoryQuery);

if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
    $category = mysqli_fetch_assoc($categoryResult);
} else {
    exit("Category not found.");
}

if (isset($_POST['update_category'])) {
    // Retrieve updated form data
    $categoryName = $_POST['category_name'];

    $updateQuery = "UPDATE categorie SET Category_Name = '$categoryName' WHERE Id_Category = $categoryId";
    $result = mysqli_query($conn, $updateQuery);

    if ($result) {
        exit(header("Location: ./categories.php"));
    } else {
        $error = "Error updating category: " . mysqli_error($conn);
    }
}
?>

<?php include_once('include/header.php'); ?>

<div class="content">
    <div class="container-fluid pt-4 px-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Modifier Catégorie</h5>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="category_name" class="form-label">Nom de catégorie</label>
                                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo $category['Category_Name']; ?>" required>
                            </div>
                            <button type="submit" name="update_category" class="btn btn-primary">mise a jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('include/footer.php'); ?>
