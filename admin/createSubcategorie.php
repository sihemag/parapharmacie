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
    $category = $_POST['category'];

    // Insert record into database
    $insertQuery = "INSERT INTO subcategorie (Name_SubCategorie, id_categorie) VALUES (?, ?)";
    $statement = mysqli_prepare($conn, $insertQuery);
    // Bind parameters
    mysqli_stmt_bind_param($statement, "si", $name, $category);
    $result = mysqli_stmt_execute($statement);

    if ($result) {
        // Subcategory inserted successfully
        exit(header("Location: ./createSubcategorie.php"));
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
                <div class="bg-light rounded h-100 p-4" style="height: 400px;">
                    <h6 class="mb-4">Ajouter une nouvelle sous-catégorie</h6>
                    <form method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" required>
                            <label for="name">Nom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="category" name="category" required>
                                <option value="">choisir une catégorie</option>
                                <?php
                                $categoryQuery = "SELECT * FROM categorie";
                                $categoryResult = mysqli_query($conn, $categoryQuery);
                                if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                                    while ($row = mysqli_fetch_assoc($categoryResult)) {
                                        echo "<option value='" . $row['Id_Category'] . "'>" . $row['Category_Name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="category">Catégorie</label>
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
