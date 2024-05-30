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
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quant = $_POST['quant'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory']; // New field for subcategory selection
    $pharmacy = $_POST['pharmacy']; // New field for pharmacy selection

    // Handle file upload
    $imageData = file_get_contents($_FILES["image"]["tmp_name"]);

    // Insert record into database
    $insertQuery = "INSERT INTO medicament (Name_Med, Description, Price, quant, Category_Id, SubCategory_id, id_pharmacy, Image_Med) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = mysqli_prepare($conn, $insertQuery);
    // Bind parameters
    mysqli_stmt_bind_param($statement, "sssiiiis", $name, $description, $price, $quant, $category, $subcategory, $pharmacy, $imageData);
    $result = mysqli_stmt_execute($statement);

    if ($result) {
        // Medication record inserted successfully
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
                    <h6 class="mb-4">Ajouter une nouvelle produit</h6>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" required>
                            <label for="name">Nom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            <label for="description">Description</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            <label for="image">télécharger l'image</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="price" name="price" required>
                            <label for="price">Prix</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="quant" name="quant" required>
                            <label for="quant">Quantité</label>
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
                        <div class="form-floating mb-3">
                            <select class="form-select" id="subcategory" name="subcategory" required>
                                <option value="">Sélectionner un sous-catégorie</option>
                                <?php
                                $pharmacyQuery = "SELECT * FROM subcategorie";
                                $pharmacyResult = mysqli_query($conn, $pharmacyQuery);
                                if ($pharmacyResult && mysqli_num_rows($pharmacyResult) > 0) {
                                    while ($row = mysqli_fetch_assoc($pharmacyResult)) {
                                        echo "<option value='" . $row['id_SubCategorie'] . "'>" . $row['Name_SubCategorie'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="subcategory">sous-catégorie</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="pharmacy" name="pharmacy" required>
                                <option value="">sélectionner une pharmacie</option>
                                <?php
                                $pharmacyQuery = "SELECT * FROM pharmacy";
                                $pharmacyResult = mysqli_query($conn, $pharmacyQuery);
                                if ($pharmacyResult && mysqli_num_rows($pharmacyResult) > 0) {
                                    while ($row = mysqli_fetch_assoc($pharmacyResult)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="pharmacy">Pharmacie</label>
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
