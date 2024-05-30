<?php 
    include("../conn.php");
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if(isset( $_GET['query'])){
        $query = $_GET['query'];

        // Perform a search in your database (replace 'products' with your actual table name)
        $sql = "SELECT * FROM medicament WHERE name LIKE '%$query%'";
        $result = mysqli_query($conn, $sql);

        // Prepare an array to store search results
        $searchResults = array();

        // Fetch search results as associative array
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }

        // Send the search results as JSON
        header('Content-Type: application/json');
        echo json_encode($searchResults);
    }

    if(isset($_SESSION['user_id'])){
        $IdUser = $_SESSION['user_id'];
        $sql = "SELECT COUNT(*) AS num_records FROM panier WHERE id_user = $IdUser ";
        // Execute the query
        $result = mysqli_query($conn, $sql);

        // Check if the query was successful
        if ($result) {
            // Fetch the result row as an associative array
            $row = mysqli_fetch_assoc($result);
            
            // Extract the count from the result row
            $num_records = $row['num_records'];
        } 
        // Free the result set
        mysqli_free_result($result);
    }
    if (isset($_POST["LoginOut"])) {
        session_destroy();
        echo "<script>alert('LogedOut Succsecfully');</script>";
        header("../HomePage/HomePage.php");
    }
    $pageName = basename($_SERVER['PHP_SELF']);
    
?>
<style><?php 
    include("header.css");
?></style>
<div class="header">
    <div class="BarName">
        <img src="../Image/logo.jpg" class="Image-1" />
        <h1>PharmaLife</h1>
    </div>
    <div class="container">
        <div>
            <a href="../HomePage/HomePage.php">
                <button class="Button <?php echo $pageName == 'HomePage.php' ? 'active' : ''; ?>">Accueil</button>
            </a>
            <a href="../Medicaments/Medicaments.php" >
                <button class="Button <?php echo $pageName == 'Medicaments.php' ? 'active' : ''; ?>">Produits</button>
            </a>
            <a href="../marques/marques.php" >
                <button class="Button <?php echo $pageName == 'marques.php' ? 'active' : ''; ?>">Marques</button>
            </a>
            <a href="../Apropos/Apropos.php" >
                <button class="Button <?php echo $pageName == 'Apropos.php' ? 'active' : ''; ?>">A propos</button>
            </a>
            <a href="../ContactUs/ContactUs.php" >
                <button class="Button <?php echo $pageName == 'ContactUs.php' ? 'active' : ''; ?>">Contacter Nous</button>
            </a>
            <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="../MesCommand/MesCommand.php" >
                <button class="Button <?php echo $pageName == 'MesCommand.php' ? 'active' : ''; ?>">Mes commandes</button>
            </a>
            <?php } ?>
        </div>
        <!-- ### -->
        <form method="post" action="../Medicaments/Medicaments.php" id="searchBox">
            <img id="searchImage" src="../Image/search.png" onclick="toggleSearch()">
            <div class="field">
                <input name="searchedText" type="text" id="searchInputHeader" placeholder="Search products">
                <div class="line"></div>
            </div>
            <!-- <button id="searchButton" type="submit" name="searchButton">search</button> -->
            <div id="searchResults"></div>
        </form>
         <!-- ### -->
        <div class="right-box">
            
            <div>
                <input onclick=toggleDarkMode() type="checkbox" class="checkbox" id="checkbox">
                <label for="checkbox" class="checkbox-label">
                    <img class="fas fa-moon" src="../Image/moon-svgrepo-com.svg"></img>
                    <img class="fas fa-sun" src="../Image/sun-svgrepo-com.svg"></img>
                    <span class="ball"></span>
                </label>
            </div>
            <a href="../panier/panier.php" class="Flex right-box1">
                <img class="Image-2" src="../Image/shopping-basket.png">
                <?php if(isset($num_records)){?>
                    <p class="Inventory"><?php echo $num_records ?></p>
                <?php } else {?>
                    <p class="Inventory">0</p>   
                <?php } ?>
            </a>
            <div class="Flex right-box2">
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <p><?php echo $_SESSION["Argent"] ?> Dz</p>
                <?php } else { ?>
                    <p>0 dz</p>
                <?php }?>
                
            </div>
            <div class="Flex right-box3">
                <div >
                    <img class="Image-4" src="../Image/user.png" />
                </div>
                <div>
                    
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <p class="p"><?php echo $_SESSION['username'] ?></p>
                        <form method="POST">
                            <Button class="ButtonLogOut" name="LoginOut" type="Submit">Déconnecter</Button>
                        </form>
                    <?php } else {?>
                        <p class="p">Utilisateur</p>
                        <p class="p"><a href="../Login/Login.php">Connexion</a> / <a href="../Register/Register.php">Cree un Compte</a> </p>
                    <?php }?>

                </div>
            </div>
        </div>
    </div>
</div>
<script> <?php include("header.js"); ?>  </script>