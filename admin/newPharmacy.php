<?php
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn'])) {
    exit(header("Location: ./signin.php"));
}

if (isset($_POST['Button_Submit'])) {
    // Retrieve form data for the pharmacy
    $name = $_POST['name'];
    $debt = $_POST['debt'];

    // Insert record into the pharmacy table
    $insertQuery = "INSERT INTO pharmacy (name, debt) 
                    VALUES (?, ?)";
    $statement = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($statement, "si", $name, $debt); // "si" indicates string and integer data types
    $result = mysqli_stmt_execute($statement);

    if ($result) {
        // Pharmacy record inserted successfully
        exit(header("Location: ./create.php"));
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
                    <h6 class="mb-4">Ajouter une nouvelle pharmacie</h6>
                    <form method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" required>
                            <label for="name">Nom de pharmacie</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="debt" name="debt" required>
                            <label for="debt">Dette</label>
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
