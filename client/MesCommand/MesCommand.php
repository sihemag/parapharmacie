<?php

    include("../conn.php");
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(isset($_SESSION['user_id'])){
        $userId = $_SESSION['user_id'];
        $sql2 = "SELECT c.*, s.*, m.Name_Med,m.Price
         FROM command c 
         JOIN subcommand s ON c.Id_Command = s.comndID 
         JOIN medicament m ON m.Id_Medicament = s.midID 
         WHERE c.Id_user = $userId";

        $result2 = mysqli_query($conn, $sql2);
        if ($result2) {
            $Commands = [];
            
            while ($row = mysqli_fetch_assoc($result2)) {
                $commandId = $row['Id_Command'];
                // Check if the command already exists in the array
                if (!isset($Commands[$commandId])) {
                    // If not, initialize the command details
                    $Commands[$commandId] = [
                        'Id_Command' => $row['Id_Command'],
                        'dateCommand' => $row['dateCommand'],
                        'statusCommand' => $row['statusCommand'],
                        'subcommands' => [] // Initialize subcommands array
                    ];
                }
                // Add subcommand information to the corresponding command
                $Commands[$commandId]['subcommands'][] = [
                    'id' => $row['id'],
                    'comndID' => $row['comndID'],
                    'midID' => $row['midID'],
                    'Name_Med' => $row['Name_Med'],
                    'quantity' => $row['qnt'],
                    'price' => $row['Price']
                ];
            }
                
            }
        }
            
        
            
        
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include('MesCommand.css') ?></style>
    <title>Mes Command</title>
</head>
<body>
    <?php
        include("../header/header.php");
    ?>
   <div class="Margin-Left">
        <h1>Command Details</h1>
        <img class="imageSubNavBar" src="../Image/MedicamentNoBackGround.png" />
    </div>

    <div class="ContainerCommand">
        <?php foreach ($Commands as $command) { ?>
            <div class="Box-Command">
                <div class="Box-Detail">
                    
                    <h2>Command ID: <?php echo $command['Id_Command']; ?></h2>
                    <h2>Date: <?php echo $command['dateCommand']; ?></h2>
                    <h2>Status: <?php if($command['statusCommand']){echo "livre" ;}else{echo "pas encore livre";} ?></h2>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Id sub Command</th>
                            <th>Name Med</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Price</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($command['subcommands'] as $subcommand) { ?>
                            <tr >
                                <td><?php echo $subcommand['id']; ?></td>
                                <td><?php echo $subcommand['Name_Med']; ?></td>
                                <td><?php echo $subcommand['quantity']; ?></td>
                                <td><?php echo $subcommand['price']; ?></td>
                                <td class="subTotal" ><?php echo $subcommand['quantity'] * $subcommand['price']; ?></td>
                            </tr>
                        <?php } ?>
                        
                    </tbody>
                    
                </table>
                <div class="TotalDiv" >
                    <h2>Total: <span class="totalPrice"> 0<span> Dz</span></span></h2>
                </div>
                
            </div>
            
            <?php } ?>
        </div>
    
    <?php include("../footer/footer.php"); ?>
    <script><?php include("MesCommand.js");?></script>
</body>
</html>