<?php
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn'])) {
    exit(header("Location: ./signin.php"));
}

// Extract the pharmacy ID from the URL
if (isset($_GET['id'])) {
    $pharmacyId = $_GET['id'];
} else {
    exit("Pharmacy ID not provided.");
}

// Fetch pharmacy details from the database
$pharmacyQuery = "SELECT * FROM pharmacy WHERE id = $pharmacyId";
$pharmacyResult = mysqli_query($conn, $pharmacyQuery);

if ($pharmacyResult && mysqli_num_rows($pharmacyResult) > 0) {
    $pharmacy = mysqli_fetch_assoc($pharmacyResult);
} else {
    exit("Pharmacy not found.");
}

if (isset($_POST['update_pharmacy'])) {
    // Retrieve updated form data
    $name = $_POST['name'];
    $debt = $_POST['debt'];

    $updateQuery = "UPDATE pharmacy SET name = '$name', debt = '$debt' WHERE id = $pharmacyId";
    $result = mysqli_query($conn, $updateQuery);

    if ($result) {
        exit(header("Location: ./pharmacy.php"));
    } else {
        $error = "Error updating pharmacy: " . mysqli_error($conn);
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
                        <h5 class="card-title">modifier Pharmacie</h5>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $pharmacy['name']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="debt" class="form-label">Dette</label>
                                <input type="number" class="form-control" id="debt" name="debt" value="<?php echo $pharmacy['debt']; ?>" required>
                            </div>
                            <button type="submit" name="update_pharmacy" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('include/footer.php'); ?>
