<?php
include("../conn.php");
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST["LogInButton"])) {

    $First_Name = $_POST["First_Name"];
    $Last_Name = $_POST["Last_Name"];
    $userName = $_POST["userName"];
    $address = $_POST["address"];
    $phonenumber = $_POST["phonenumber"];
    $Password = $_POST["password"];
    $sql = "INSERT INTO users (First_Name, Last_Name, userName, password, Argent, Privilage, addres, phonenumber) VALUES ('$First_Name', '$Last_Name', '$userName', '$Password', 0, 'User', '$address', '$phonenumber')";
    $result = mysqli_query($conn, $sql);
    if($result){
        
        header("location: ../Login/Login.php");
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include('Register.css') ?></style>
    <title>Sign Up</title>
</head>

<body>
    <?php include("../header/header.php") ?>
<div class="FormContainer">
        <h2 id='NameReg'>Connexion</h2>
        <form method="POST">
            <input id="Input" name="First_Name" type="text" placeholder="Nom" />
            <input id="Input" name="Last_Name" type="text" placeholder="Prénom" />
            <input id="Input" name="userName" type="text" placeholder="Nom d'utilisateur" />
            <input id="Input" name="address" type="text" placeholder="Address" />
            <input id="Input" name="phonenumber" type="text" placeholder="Numéro de téléphone" />
            <input id="Input" name="password" type="password" placeholder="mot passe" />
            
            <div class="buttonDiv">
                <button class="LoginIncontainer" type="submit" name="LogInButton">Connexions</button>
            </div>
        </form>
    </div>
</div>
    <?php include("../footer/footer.php") ?>
</body>

</html>