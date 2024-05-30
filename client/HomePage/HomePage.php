<?php
ob_start();
include("../conn.php");

/* $sql2 = "SELECT Id_Medicament,Name_Med, Image_Med,Price,Description FROM medicament ORDER BY Id_Medicament DESC LIMIT 8";

$result2 = $conn->query($sql2);
$products = $result2->fetch_all(MYSQLI_ASSOC); */

$sql1 = "SELECT Id_Medicament, Name_Med, Image_Med, Price, Description,Category_Id	 
        FROM medicament m JOIN categorie c ON m.Category_Id = c.Id_Category WHERE c.Category_Name='Sales' LIMIT 12";
/* ORDER BY Id_Medicament DESC 
        LIMIT 8 */
$result = $conn->query($sql1);
$Products = $result->fetch_all(MYSQLI_ASSOC);

// Extracting the first product from the result set
$firstProduct = array_shift($Products);

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['ButtonCart'])){
    if(isset($_SESSION['user_id'])){
        $idMed = $_POST['id'];
        $idUser = $_SESSION['user_id'];
        
        // Prepare the SQL statement with placeholders
        $sql3 = "INSERT INTO panier (Id_Med, Id_user, Quantity) VALUES ($idMed, $idUser, 1)";
        mysqli_query($conn, $sql3);
        
    } else {
        echo "<script> alert('You must be logged in') </script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <title>Home</title>
    <style><?php include('HomePage.css') ?></style>
</head>
<body>
    <?php
        include("../header/header.php");
    ?>
    <?php   if(isset($firstProduct)){   ?>
    <div class="grid-container">
        <div class="item1">
            <div>
                <div class="item1Text">
                    <h3 class="text1" >Offre special</h3>
                    <h2 class="textp1 textName"><?php echo $firstProduct['Name_Med']?></h2>
                   
                    <h3 class="textR">remise week-end</h3>
                    <h1 class="textp1 text2"><?php echo $firstProduct['Price']?>.00 Dz</h1>
                </div>
                <div class="Item1DivButton">
                    <Button class="Item1Button"><a id='buttonlink' href="../PageCommand/PageCommand.php?id=<?php echo $firstProduct['Id_Medicament'] ?>">Achetez maintenant</a></Button>
                </div>
                
            </div>
            <div class="ImageContainer1">
                <img class="image-First" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($firstProduct['Image_Med']); ?>" />
            </div>
        </div>
        <?php
            if(isset($Products)){
            foreach ($Products as $Product) {
        ?>
        <a href="../PageCommand/PageCommand.php?id=<?php echo $Product['Id_Medicament'] ?>" class="itemX box small-box">
            <div>
            <h4 class="text1X">Offre special</h4>
            <h3 class="textpX"> <?php echo $Product['Name_Med'] ?> </h3>
            
            <h4 class="textRX">remise week-end</h4>
            <h3 class="textp text2X"><?php echo $Product['Price'] ?>.00 Dz</h3>
            </div>
            <div class="ImageContainer1">
                <img class="image-First" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($Product['Image_Med']); ?>"  />
            </div>
        </a>
        <?php }}?>
    </div> 
    <?php } else {?>
            <div class="EmptySales">
                <h3>No Item For Sales Yet</h3>
            </div>
    <?php }?>
    <?php include("../footer/footer.php"); ?> 
    <script><?php include("HomePage.js");?></script>

</body>
</html>
<?php

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and if they are a new user (no records in panier)
if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    $sqlCheckPanier = "SELECT COUNT(*) AS num_records FROM command WHERE Id_user = $userId";
    $resultCheckPanier = mysqli_query($conn, $sqlCheckPanier);
    $rowCheckPanier = mysqli_fetch_assoc($resultCheckPanier);
    $numRecords = $rowCheckPanier['num_records'];
    
    if($numRecords == 0) {
        // Display discount alert
        echo "<script>alert('Salut! vous bénéficiez de 5% de réduction sur votre premiére commande.');</script>";
    }
}
ob_end_flush();
?>

