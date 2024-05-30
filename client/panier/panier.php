<?php
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include("../conn.php");
    if(isset($_SESSION['user_id'])){
        $IdUser = $_SESSION['user_id'];
        $sql2 = "SELECT Image_Med,Name_Med,Price,Quantity,Id_Product,Id_Med,m.SubCategory_id FROM panier p JOIN medicament m ON p.Id_Med = m.Id_Medicament WHERE p.Id_user = $IdUser";
        $result2 = mysqli_query($conn, $sql2);
        if ($result2) {
            // Fetch the result row as an associative array
            $Paniers = mysqli_fetch_all($result2);
        } 
    }else{
        header("../HomePage/HomePage.php");
    }

    $suggestedProducts = []; // Array to store suggested products
    $categoriesInCart = []; // Assuming you have an array of category IDs in the cart
    
    if(isset($Paniers)) {
      
        foreach($Paniers as $Panier) {
            $categoryId = $Panier[6]; // Assuming the category ID is at index 6
            $categoriesInCart[] = $categoryId;
        }
    }
    $categoriesInCart = array_unique($categoriesInCart);
    // Shuffle the array of category IDs to randomize the order
    shuffle($categoriesInCart);
    $slicedArray = array_slice($categoriesInCart, 0, 3);
   
    
    // Iterate through the shuffled category IDs to fetch suggestions
    function isProductInPanier($Paniers, $productId) {
        
        foreach ($Paniers as $Panier) {
            
            if ($Panier[5] == $productId) {
                return true; // Product found in panier
            }
        }
        return false; // Product not found in panier
    }
    
    // Fetch and process suggested products
    if(isset($slicedArray)){
        $suggestedProducts = []; // Array to store suggested products
        
        foreach($slicedArray as $subCategoryId) {
            // Fetch random products from the specified category
            $sql5 = "SELECT Name_Med, Image_Med, Id_Medicament, Price FROM medicament WHERE SubCategory_id = $subCategoryId ORDER BY RAND() LIMIT 1";
            $result5 = mysqli_query($conn, $sql5);
            $row = mysqli_fetch_assoc($result5);
            
            // Check if the fetched product is not already in the panier
            $productId = $row['Id_Medicament'];
            if (!isProductInPanier($Paniers, $productId)) {
                // If not in panier, add to suggested products array
                $suggestedProducts[] = $row;
            }
        }
        
    }
    
if(!empty($_POST)) {
    // Iterate through POST variables
    foreach ($_POST as $key => $value) {
        // Check if the key starts with "UpdateButton_"
        if (strpos($key, 'UpdateButton_') === 0) {
            // Extract the product ID from the key
            $productId = substr($key, strlen('UpdateButton_'));

            // Use the product ID to update the corresponding quantity
            $IdProductUpdate = $_POST['Save_IdQuantity_' . $productId];
            $QuantityUpdate = $_POST['quantityNumber_' . $productId];

            $sql6 = "UPDATE panier SET Quantity = $QuantityUpdate WHERE Id_Product = $IdProductUpdate";
            mysqli_query($conn, $sql6);
            header("Refresh: 0");
        }
    }
}
    if(isset($_POST["ContinueCommand"])){
        header("Location: ../HomePage/HomePage.php");
    }

    if(isset($_POST["DeleteButton"])){
        $IdProduct = $_POST["Id_Panier"];
        $sql3 = "DELETE FROM panier WHERE Id_Product = $IdProduct";
        mysqli_query($conn, $sql3);
        header('Refresh: 0');
    }
    $LetBeShown = false;
    if(isset($_SESSION['user_id'])){
        
        $userId = $_SESSION['user_id'];
        $discount=0;
        $sqlCheckPanier = "SELECT COUNT(*) AS num_records FROM command WHERE Id_user = $userId";
        $resultCheckPanier = mysqli_query($conn, $sqlCheckPanier);
        $rowCheckPanier = mysqli_fetch_assoc($resultCheckPanier);
        $numRecords = $rowCheckPanier['num_records'];
        if($numRecords == 0) {
            // Display discount alert
            $discount=0.05;
        }
    }
    
    
    
    if (isset($_POST['makingOrder'])) {
        // Step 2: Loop Through Cart Items
        if(isset($Paniers)){
            $IdUser = $_SESSION['user_id'];
            $orderSql = "INSERT INTO command (Id_user, statusCommand,discount) VALUES ($IdUser, 0,$discount)";
            $orderResult = mysqli_query($conn, $orderSql);
            // Retrieve the ID of the last inserted command
            $commandId = mysqli_insert_id($conn);
            foreach ($Paniers as $Panier) {
                $productId = $Panier[5];
                $quantity = $Panier[3];
                // Step 3: Insert Order Data
                if ($orderResult) {
                    // Insert into subcommand table using the obtained command ID
                    $subCommSql = "INSERT INTO subcommand (comndID, midID, qnt) VALUES ($commandId, $productId, $quantity)";
                    $subCommResult = mysqli_query($conn, $subCommSql);
                    
                    if ($subCommResult) {
                        // Step 4: Delete Cart Items
                        $deleteSql = "DELETE FROM panier WHERE Id_user = $IdUser AND Id_Med = $productId";
                        $deleteResult = mysqli_query($conn, $deleteSql);
                        
                        if ($deleteResult) {
                            $LetBeShown = true;
                        } else {
                            // Handle delete error
                            echo "Error deleting cart items!";
                        }
                    } else {
                        // Handle subcommand insert error
                        echo "Error placing subcommand!";
                    }
                } else {
                    // Handle order insert error
                    echo "Error placing order!";
                }
            }
        }
        // Step 5: Provide Confirmation
        // Provide confirmation message to the user
    } else {
        // Handle cart retrieval error
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include('panier.css') ?></style>
    <title>Mon Panier</title>
</head>
<body>
    <?php
        include("../header/header.php");
    ?>
    <div class="Margin-Left">
        <h1>Panier</h1>
        <img class="imageSubNavBar" src="../Image/MedicamentNoBackGround.png" />
    </div>
    <div class="Container">
        <div class="Left-Container">
            <table >
                <thead>
                    <tr>
                        <th></th>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantite</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($Paniers)){
                        foreach($Paniers as $Panier){ ?>
                    <tr>
                        <td><img class="Image-Panier" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($Panier[0]); ?>" /></td>
                        <td><?php echo $Panier[1] ?></td>
                        <td><?php echo $Panier[2] ?> Dz</td>
                        <td> 
                            <form method="post" class="QuantityTd">
                                <input hidden name="Save_IdQuantity_<?php echo $Panier[4]; ?>" value="<?php echo $Panier[4] ;?>" />
                                <span class="quantity-btn" data-action="decrement" id="decrement_<?php echo $Panier[4]; ?>">-</span>
                                <span class="quantity"><?php echo $Panier[3] ?></span>
                                <span class="quantity-btn" data-action="increment" id="increment_<?php echo $Panier[4]; ?>">+</span>
                                <input hidden name="quantityNumber_<?php echo $Panier[4]; ?>" />
                                <button class="SaveButton" type="submit" name="UpdateButton_<?php echo $Panier[4]; ?>" style="display:none;"> Save </button>
                            </form>
                        </td>
                        <td class='totalProduct'><?php echo $Panier[2] * $Panier[3] ?> Dz</td>
                        <td>
                            <form method="post">
                                <Button name="DeleteButton" type="submit" class="ButtonDelete">
                                    <input name="Id_Panier" hidden value="<?php echo $Panier[4] ;?>" />
                                    <img class="Delete" src="../Image/trash-bin-trash-svgrepo-com.svg" />
                                </Button>
                            </form>
                        </td>
                    </tr>
                    <?php }}?>
                </tbody>
            </table>
        </div>
        <div class="Right-Container">
            <h3>Total du panier</h3>
            <div class="addLine Space">
                <h2>total</h2>
                <div>
                    <p>Total:</p>
                    <p id="Total">0 dz</p>
                </div>
            </div>
            <br>
            <div class="addLine Space">
                <h2>Livraison</h2>
                <div>
                    <p>Total:</p>
                    <p id='TotalLiv'>0 dz</p>
                </div>
            </div>
            <br>
            
            <div class="Space">
                <h2>total</h2>
                <div>
                    <p>Total:</p>
                    <p id="TotalEvery">0 dz</p>
                </div>
            </div>
            <div class="Space">
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
                            echo "<h4 style='color: red; font-style: italic;'>Vous bénéficiez d'une remise de 5% sur votre premier achat.</h4>";
                        }
                    }
                ?>
            </div>
            <form method="post" class="formValidate">
                <input name="totalPrice" id='totalPrice' hidden value="" />
                <Button name="makingOrder" class="ButtonPanier">Passer La Command</Button> 
            </form>
        </div>
    </div>
    <div class="suggestedDiv">
        <?php 
            foreach($suggestedProducts as $suggestedProduct){ 
                if(isset($suggestedProduct) ){ 
                    ?>
            <a class="link" href="../PageCommand/PageCommand.php?id=<?php echo $suggestedProduct["Id_Medicament"] ?>">
                <img class="SuggestedImage" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($suggestedProduct["Image_Med"]); ?>" />
                <div>
                    <h2><?php echo $suggestedProduct['Name_Med'] ?></h2>
                    <h4 id="PriceSuggested" ><?php echo $suggestedProduct['Price'] ?>.00 Dz</h4>
                </div>
                
            </a>
        <?php }} ?>
    </div>
    <?php if($LetBeShown){ ?>
    <div class="BlurredBackground"></div>

    <div class="ConfirmeDiv">
        <div class="Margin-Left">
            <h1>Commande Confirmer</h1>
            <img class="imageSubNavBar" src="../Image/MedicamentNoBackGround.png" />
        </div>
        <div class="SubConfirmeDiv">
            <img class="ConfirmeImage" src="../Image/checked.png" />
            <h3>votre commande est confirmée avec succes, vous recevrez un appelle pour pour une confirmation</h3>    
        </div>
        <form method="post" class="ButtonContinue" >
            <button class="ButtonPanier" name="ContinueCommand" >Continue</button>
        </form>
        
    </div>
    <?php } ?>
    <?php include("../footer/footer.php"); ?> 
    <script> <?php include("panier.js"); ?>  </script>
</body>
</html>