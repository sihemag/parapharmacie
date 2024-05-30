<?php
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn'])) {
    exit(header("Location: ./signin.php"));
}

if (isset($_POST['Button_Submit'])) {
    // Retrieve form data
    $name = $_POST['name'];
    // Handle file upload
    $imageData = file_get_contents($_FILES["image"]["tmp_name"]);

    // Insert record into database
    $insertQuery = "INSERT INTO marques (marques_name, img) VALUES (?, ?)";
    $statement = mysqli_prepare($conn, $insertQuery);
    // Bind parameters
    mysqli_stmt_bind_param($statement, "ss", $name, $imageData);
    $result = mysqli_stmt_execute($statement);

    if ($result) {
        // Brand record inserted successfully
        exit(header("Location: ./createMarques.php"));
    } else {
        // Error occurred while inserting record
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php include_once('include/header.php'); ?>

<div class="content">
    <!-- Form Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
            <div class="col-md-8 col-lg-10 col-xl-8 text-center">
                <div class="bg-light rounded h-100 p-4" style="height: 600px;">
                    <h6 class="mb-4">Ajouter une nouvelle marque</h6>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" required>
                            <label for="name">Marque</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            <label for="image">télécharger une image</label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" name="Button_Submit" class="btn btn-primary">Soumettre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Form End -->
</div>

<?php include_once('include/footer.php'); ?>
