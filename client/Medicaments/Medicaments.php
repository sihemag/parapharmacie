<?php
include("../conn.php");
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
$sql2 = "SELECT m.*, c.Category_Name,s.Name_SubCategorie FROM medicament m JOIN categorie c ON m.Category_Id = c.Id_Category JOIN subcategorie s On s.id_SubCategorie = m.SubCategory_id";
$result2 = mysqli_query($conn, $sql2);
if ($result2) {
    // Fetch the result rows as an associative array
    $Medics = mysqli_fetch_all($result2, MYSQLI_ASSOC);
}

$sql3 = "SELECT * FROM categorie ORDER BY Category_Name ASC";
$result3 = mysqli_query($conn, $sql3);
if ($result3) {
    // Fetch the result rows as an associative array
    $categories = mysqli_fetch_all($result3, MYSQLI_ASSOC);
}

$sqlSubcategories = "SELECT * FROM subcategorie ORDER BY Name_SubCategorie ASC";
$resultSubcategories = mysqli_query($conn, $sqlSubcategories);
$subcategories = mysqli_fetch_all($resultSubcategories, MYSQLI_ASSOC);


if(isset($_POST['ButtonCart'])){
    if(isset($_SESSION['user_id'])){
        $idMed = $_POST['id'];
        $idUser = $_SESSION['user_id'];
        // Prepare the SQL statement with placeholders
        $sql4 = "INSERT INTO panier (Id_Med, Id_user, Quantity) VALUES ($idMed, $idUser, 1)";
        mysqli_query($conn, $sql4);
        
    } else {
        echo "<script> alert('You must be logged in') </script>";
    }
}

if(isset($_POST['searchedText'])){
    $searchTerm =  $_POST['searchedText'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include('Medicaments.css') ?></style>
    <title>Produits</title>
</head>
<body>
    <?php include("../header/header.php"); ?>
    <div class="Margin-Left">
        <h1>Produits</h1>
        <img class="imageSubNavBar" src="../Image/MedicamentNoBackGround.png" />
    </div>
    <div class="Container">
        <div class="Left-Container">
            <input class="InputText" type="text" id="searchInput" placeholder="Recherche un produit" <?php if(isset($searchTerm)) echo "value='" . htmlspecialchars($searchTerm) . "'"; ?>/>
            <div class="Flex-Col">
                <?php foreach ($categories as $category) {
                    $categoryId = $category['Id_Category'];
                    $categoryName = $category['Category_Name']; ?>
                    <div class="checkbox-container">
                        <input type="checkbox" id="<?php echo $categoryId; ?>" class="categoryCheckbox">
                        <label class="labelCategirie" for="<?php echo $categoryId; ?>"><img class="ImageSvgCheck" src="../Image/arrow-to-down-right-svgrepo-com.svg" /><?php echo $categoryName; ?></label>
                        <div class="subCategorie">
                            <?php foreach ($subcategories as $subcategorie) { 
                                if ($subcategorie['id_categorie'] == $categoryId) { ?>
                                    <div class="subBox subcategory_<?php echo $categoryId; ?>">
                                        <input type="checkbox" id="<?php echo $subcategorie['id_SubCategorie']; ?>" class="subcategoryCheckbox subcategory_<?php echo $categoryId; ?>">
                                        <label for="<?php echo $subcategorie['id_SubCategorie']; ?>"><?php echo $subcategorie['Name_SubCategorie']; ?></label>
                                    </div>
                            <?php } } ?>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
        <div class="Right-Container">
            <?php foreach ($Medics as $Medic){?>
                <div class="RightBox <?php echo ($Medic['quant'] == 0) ? 'inactive' : 'active'; ?>" data-category="<?php echo $Medic['Category_Name']; ?>" 
                data-subcategory="<?php echo $Medic['Name_SubCategorie']; ?>"
                > 
                    <a href="../PageCommand/PageCommand.php?id=<?php echo $Medic['Id_Medicament'] ?>">
                    <div class="ImageContainer">
                        <img class="Image-Box" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($Medic["Image_Med"]); ?>" />
                    </div>
                    <h3 class="WhitesPace"> <?php echo $Medic["Name_Med"] ?></h3>
                    </a>
                    
                    <form method="post" class="space-panier">
                        <p id="price"><?php echo $Medic["Price"] ?> <span id="curencyPrice">Dz</span> </p>
                        <input name='id' value="<?php echo $Medic['Id_Medicament']?>" hidden>
                        <Button name="ButtonCart" type="submit" class="Button-panier"><img class="Image-panier" src="../Image/addB.png" /></Button>
                    </form>
                    
                </div>
            <?php 
            }?>
        </div>
    </div>
    <?php include("../footer/footer.php"); ?> 
    <script><?php include("Medicaments.js");?></script>
</body>
</html>
