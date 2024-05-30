<?php
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn'])) {
    exit(header("Location:./signin.php"));
}

// Extract the product ID from the URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
} else {
    exit("Product ID not provided.");
}

// Fetch product details from the database
$productQuery = "SELECT * FROM medicament WHERE Id_Medicament = $productId";
$productResult = mysqli_query($conn, $productQuery);

if ($productResult && mysqli_num_rows($productResult) > 0) {
    $product = mysqli_fetch_assoc($productResult);
} else {
    exit("Product not found.");
}

if (isset($_POST['update_product'])) {
    // Retrieve updated form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quant = $_POST['quant'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $id_pharmacy = $_POST['id_pharmacy']; // Corrected field name
    
    // Update information
    $updateInfoQuery = "UPDATE medicament SET Name_Med='$name', Description='$description', Price='$price', quant='$quant', Category_Id='$category', SubCategory_id='$subcategory', id_pharmacy='$id_pharmacy' WHERE Id_Medicament='$productId'";
    $updateInfoResult = mysqli_query($conn, $updateInfoQuery);
    
    // Check if a new image is uploaded
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        // Update picture
        $updateImageQuery = "UPDATE medicament SET Image_Med='$image' WHERE Id_Medicament='$productId'";
        $updateImageResult = mysqli_query($conn, $updateImageQuery);
    }

    if ($updateInfoResult) {
        exit(header("Location:./prodects.php"));
    } else {
        $error = "Error updating prodect: " . mysqli_error($conn);
    }
}
?>

<?php include_once('include/header.php');?>

<div class="content">
    <div class="container-fluid pt-4 px-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">modifier le Produit</h5>
                        <?php if (isset($error)) :?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error;?>
                            </div>
                        <?php endif;?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['Name_Med'];?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $product['Description'];?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Prix</label>
                                <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['Price'];?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="quant" class="form-label">Quantité</label>
                                <input type="number" class="form-control" id="quant" name="quant" value="<?php echo $product['quant'];?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Catégorie</label>
                                <select class="form-select" id="category" name="category" required>
                                    <?php
                                    $categoryQuery = "SELECT * FROM categorie";
                                    $categoryResult = mysqli_query($conn, $categoryQuery);
                                    if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                                        while ($row = mysqli_fetch_assoc($categoryResult)) {
                                            $selected = ($row['Id_Category'] == $product['Category_Id']) ? 'selected' : '';
                                            echo "<option value='".$row['Id_Category']."' $selected>" . $row['Category_Name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="subcategory" name="subcategory" required>
                                    <option value="">Sélectionner une Sous-catégorie</option>
                                    <?php
                                    $pharmacyQuery = "SELECT * FROM subcategorie";
                                    $pharmacyResult = mysqli_query($conn, $pharmacyQuery);
                                    if ($pharmacyResult && mysqli_num_rows($pharmacyResult) > 0) {
                                        while ($row = mysqli_fetch_assoc($pharmacyResult)) {
                                            echo "<option value='".$row['id_SubCategorie']."'>" . $row['Name_SubCategorie'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="subcategory">Sous-catégorie</label>
                            </div>
                            <div class="mb-3">
                                <label for="id_pharmacy" class="form-label">Pharmacie</label>
                                <select class="form-select" id="id_pharmacy" name="id_pharmacy" required>
                                    <?php
                                    $pharmacyQuery = "SELECT * FROM pharmacy";
                                    $pharmacyResult = mysqli_query($conn, $pharmacyQuery);
                                    if ($pharmacyResult && mysqli_num_rows($pharmacyResult) > 0) {
                                        while ($row = mysqli_fetch_assoc($pharmacyResult)) {
                                            $selected = ($row['id'] == $product['id_pharmacy']) ? 'selected' : '';
                                            echo "<option value='".$row['id']."' $selected>" . $row['name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">image du produit</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                            <button type="submit" name="update_product" class="btn btn-primary">mise a jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('include/footer.php');?>
