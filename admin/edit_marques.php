<?php
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn'])) {
    exit(header("Location: ./signin.php"));
}

// Extract the marques ID from the URL
if (isset($_GET['id'])) {
    $marquesId = $_GET['id'];
} else {
    exit("Marques ID not provided.");
}

// Fetch marques details from the database
$marquesQuery = "SELECT * FROM marques WHERE id = $marquesId";
$marquesResult = mysqli_query($conn, $marquesQuery);

if ($marquesResult && mysqli_num_rows($marquesResult) > 0) {
    $marques = mysqli_fetch_assoc($marquesResult);
} else {
    exit("Marques not found.");
}

if (isset($_POST['update_marques'])) {
    // Retrieve updated form data
    $name = $_POST['name'];

    // Check if a new image is uploaded
    if (!empty($_FILES["image"]["tmp_name"])) {
        $img = file_get_contents($_FILES["image"]["tmp_name"]);
        // Update query with image
        $updateQuery = "UPDATE marques SET marques_name = ?, img = ? WHERE id = $marquesId";
        $statement = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($statement, "ss", $name, $img);
    } else {
        // Update query without image
        $updateQuery = "UPDATE marques SET marques_name = ? WHERE id = $marquesId";
        $statement = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($statement, "s", $name);
    }

    $result = mysqli_stmt_execute($statement);

    if ($result) {
        exit(header("Location: ./marques.php"));
    } else {
        $error = "Error updating marques: " . mysqli_error($conn);
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
                        <h5 class="card-title">Modifier Marques</h5>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $marques['marques_name']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">télecharger une image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                            <button type="submit" name="update_marques" class="btn btn-primary">mise a jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('include/footer.php'); ?>
