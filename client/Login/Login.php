<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../conn.php"; // include database connection code

if (isset($_POST["LogInButton"])) {
    $userName = $_POST["userName"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE userName = '$userName'";
    $result = mysqli_query($conn, $sql);

    // Check if any rows were returned
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Verify the password
        if ($password == $row["password"] ) {
            // Set session variables
            $_SESSION["user_id"] = $row['Id_user'];
            $_SESSION["username"] = $row['userName'];
            $_SESSION["Argent"] = $row['Argent'];
            $_SESSION["Privilage"] = $row['Privilage'];

            // Redirect to home page or wherever you want to go
            header("Location: ../HomePage/HomePage.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid username.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./LogIn.css">
    <title>Connexion</title>
</head>

<body >
<?php
        include("../header/header.php");
    ?>
<div class="Container-Login">

    <div class="FormContainer">
        <h2 id='NameLog' >Connexion</h2>
        <form method="POST">
            <input id="Input" name="userName" type="text" placeholder="Nom utilisateur" />
            <input id="Input" name="password" type="password" placeholder="Mot passe" />
            <div class="buttonDiv">
                <button class="LoginIncontainer" type="submit" name="LogInButton">Connexion</button>
            </div>
        </form>
    </div>
</div>
<?php
        include("../footer/footer.php");
    ?>
    
</body>

</html>