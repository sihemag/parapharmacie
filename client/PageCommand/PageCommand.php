<?php 
    include('../conn.php');
    if(isset($_GET['id'])){
        $id_Med = $_GET['id'];        
        $sql1 = "SELECT * FROM medicament WHERE Id_Medicament = $id_Med";
        $result1 = mysqli_query($conn, $sql1);
        if ($result1 && mysqli_num_rows($result1) > 0) {
            // Fetch the result row as an associative array
            $Medic = mysqli_fetch_assoc($result1);
            //print_r($Medic);
        }
    }
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
        if (isset($_POST["ButtonSub"])) {
            if(isset($_SESSION['user_id'])){
            // Get the quantity value
            $quantity = $_POST["quantity"];
            $IdUser = $_SESSION['user_id'];
            $medicament_id = $id_Med;

            // Check if the medicament_id already exists for the current user
            $check_query = "SELECT * FROM panier WHERE Id_Med = $medicament_id AND Id_user = $IdUser";
            $check_result = mysqli_query($conn, $check_query);
    
            if (mysqli_num_rows($check_result) > 0) {
                // If the medicament_id exists, update the quantity
                $update_query = "UPDATE panier SET Quantity = Quantity + $quantity WHERE Id_Med = $medicament_id AND Id_user = $IdUser";
                $result2 = mysqli_query($conn, $update_query);
            } else {
                // If the medicament_id does not exist, insert a new record
                $insert_query = "INSERT INTO panier (Id_Med , Id_user, Quantity) VALUES ($medicament_id, $IdUser, $quantity)";
                $result2 = mysqli_query($conn, $insert_query);
            }
        }
        else{
            echo "<script>alert('Please Log In First');</script>";
        }
    }
     
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include('PageCommand.css') ?></style>
    <title>PageCommand</title>
</head>
<body>
    <?php
        include("../header/header.php");
    ?>
    <?php  if(isset($Medic)){?>
    <div class="Container-Command">
        <div>
        <img class="Image-Med" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($Medic["Image_Med"]); ?>"/>
        </div>
        <div>
            <h2>
                <?php echo $Medic['Name_Med'] ?> 
            </h2>
            <h5 id="Price">
                <?php echo $Medic['Price'] ?>.00 Dz
            </h5>
            <p id='Description'><b>Description</b></p>
            <p id='actualDes'>
                <?php echo $Medic['Description'] ?>
            </p>
            <div class="Container-Quantite">
                <div class="box-Quantite">
                    <h3>Quantité</h3>
                    <div class="box-Add">
                        <input type="hidden" name="maxQuantity" id="maxQuantity" value="<?php echo $Medic['quant']; ?>">
                        <Button id="decrement" class="Button-quan" >-</Button>
                        <p id="quantity">1</p>
                        <Button id="increment" class="Button-quan">+</Button>
                    </div>
                </div>
                <form class="Button-Container" method="post"> <!-- Replace 'process.php' with your PHP file name -->
                    <input type="hidden" name="quantity" id="quantityInput" value="1"> <!-- Hidden input field to hold the quantity value -->
                    <Button name="ButtonSub" type="submit" class="Button-panier">Ajouter au pannier</Button>
                </form>
                
            </div>
            

        </div>
    </div>
    <?php } else { ?>
    <p>No medication found with the given ID.</p>
    <?php } ?>
    <script> <?php include('PageCommand.js');?></script>
</body>
</html>