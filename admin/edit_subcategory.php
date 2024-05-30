<?php
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn'])) {
    exit(header("Location: ./signin.php"));
}

// Extract the subcategory ID from the URL
if (isset($_GET['id'])) {
    $subcategoryId = $_GET['id'];
} else {
    exit("Subcategory ID not provided.");
}

// Fetch subcategory details from the database
$subcategoryQuery = "SELECT * FROM subcategorie WHERE id_SubCategorie = $subcategoryId";
$subcategoryResult = mysqli_query($conn, $subcategoryQuery);

if ($subcategoryResult && mysqli_num_rows($subcategoryResult) > 0) {
    $subcategory = mysqli_fetch_assoc($subcategoryResult);
} else {
    exit("Subcategory not found.");
}

if (isset($_POST['update_subcategory'])) {
    // Retrieve updated form data
    $name = $_POST['name'];
    $category = $_POST['category'];

    $updateQuery = "UPDATE subcategorie SET Name_SubCategorie = ?, id_categorie = ? WHERE id_SubCategorie = $subcategoryId";
    $statement = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($statement, "si", $name, $category);
    $result = mysqli_stmt_execute($statement);

    if ($result) {
        exit(header("Location: ./subcategories.php"));
    } else {
        $error = "Error updating subcategory: " . mysqli_error($conn);
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
                        <h5 class="card-title">Modifier sous-catégorie</h5>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $subcategory['Name_SubCategorie']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">catégorie</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">choisir une catégorie</option>
                                    <?php
                                    $categoryQuery = "SELECT * FROM categorie";
                                    $categoryResult = mysqli_query($conn, $categoryQuery);
                                    if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                                        while ($row = mysqli_fetch_assoc($categoryResult)) {
                                            $selected = ($row['Id_Category'] == $subcategory['id_categorie']) ? "selected" : "";
                                            echo "<option value='" . $row['Id_Category'] . "' $selected>" . $row['Category_Name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" name="update_subcategory" class="btn btn-primary">mise a jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('include/footer.php'); ?>
