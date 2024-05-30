<?php
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn'])) {
    exit(header("Location: ./signin.php"));
}

// Fetch subcategories from the database
$subcategoriesQuery = "SELECT * FROM subcategorie";
$subcategoriesResult = mysqli_query($conn, $subcategoriesQuery);

?>

<?php include_once('include/header.php'); ?>

<div class="content">
    <div class="container-fluid pt-4 px-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sous-catégories</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Catégorie</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($subcategoriesResult && mysqli_num_rows($subcategoriesResult) > 0) : ?>
                                    <?php while ($row = mysqli_fetch_assoc($subcategoriesResult)) : ?>
                                        <tr>
                                            <th scope="row"><?php echo $row['id_SubCategorie']; ?></th>
                                            <td><?php echo $row['Name_SubCategorie']; ?></td>
                                            <!-- Fetch and display category name -->
                                            <td><?php echo getCategoryName($row['id_categorie']); ?></td>
                                            <td>
                                                <a href="edit_subcategory.php?id=<?php echo $row['id_SubCategorie']; ?>" class="btn btn-primary btn-sm">Modifier</a>
                                                <!-- Add delete button if needed -->
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4">aucune sous-catégorie trouvée</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('include/footer.php'); ?>

<?php
// Function to fetch category name based on category ID
function getCategoryName($categoryId)
{
    global $conn;
    $categoryQuery = "SELECT Category_Name FROM categorie WHERE Id_Category = $categoryId";
    $categoryResult = mysqli_query($conn, $categoryQuery);
    if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
        $category = mysqli_fetch_assoc($categoryResult);
        return $category['Category_Name'];
    } else {
        return "Unknown Category";
    }
}
?>
