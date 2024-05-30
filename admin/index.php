<?php 
include_once('config/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LogedIn']) || $_SESSION['Privilage'] !== 'Admin') {
    exit(header("Location: ./signin.php"));
}

// Handle command confirmation
if (isset($_POST['confirm_command'])) {
    $commandId = $_POST['command_id'];

    // Update command status to confirmed
    $updateQuery = "UPDATE command SET statusCommand = 1 WHERE Id_Command = $commandId";
    mysqli_query($conn, $updateQuery);

    // Update medicament table to adjust available quantity
    $subCommandQuery = "SELECT * FROM subcommand WHERE comndID = $commandId";
    $subCommandResult = mysqli_query($conn, $subCommandQuery);

    while ($subCommandRow = mysqli_fetch_assoc($subCommandResult)) {
        $medicamentId = $subCommandRow['midID'];
        $orderedQuantity = $subCommandRow['qnt'];

        // Fetch current available quantity
        $medicamentQuery = "SELECT quant FROM medicament WHERE Id_Medicament = $medicamentId";
        $medicamentResult = mysqli_query($conn, $medicamentQuery);
        $medicamentRow = mysqli_fetch_assoc($medicamentResult);
        $currentQuantity = $medicamentRow['quant'];

        // Calculate new available quantity
        $newQuantity = $currentQuantity - $orderedQuantity;

        // Update medicament table
        $updateMedicamentQuery = "UPDATE medicament SET quant = $newQuantity WHERE Id_Medicament = $medicamentId";
        mysqli_query($conn, $updateMedicamentQuery);
    }

    // Fetch distinct pharmacy IDs associated with medications in the command
    $pharmacyIdQuery = "SELECT DISTINCT m.id_pharmacy
                        FROM subcommand s
                        INNER JOIN medicament m ON s.midID = m.Id_Medicament
                        WHERE s.comndID = $commandId";
    $pharmacyIdResult = mysqli_query($conn, $pharmacyIdQuery);

    while ($pharmacyIdRow = mysqli_fetch_assoc($pharmacyIdResult)) {
        $pharmacyId = $pharmacyIdRow['id_pharmacy'];

        // Calculate total price of medications for this pharmacy
        $priceQuery = "SELECT SUM(m.Price * s.qnt) AS total_price
                        FROM subcommand s
                        INNER JOIN medicament m ON s.midID = m.Id_Medicament
                        WHERE s.comndID = $commandId AND m.id_pharmacy = $pharmacyId";
        $priceResult = mysqli_query($conn, $priceQuery);
        $priceRow = mysqli_fetch_assoc($priceResult);
        $totalPrice = $priceRow['total_price'];

        // Add 30% of the total price to the pharmacy's debt
        $debtIncrease = $totalPrice * 0.3;

        // Update pharmacy debt
        $updatePharmacyDebtQuery = "UPDATE pharmacy SET debt = debt + $debtIncrease WHERE id = $pharmacyId";
        mysqli_query($conn, $updatePharmacyDebtQuery);
    }
}

// Fetch users' commands
$commandQuery = "SELECT * FROM command";
$commandResult = mysqli_query($conn, $commandQuery);

include_once('include/header.php');
?>

<!-- Content Start -->
<div class="content">
    <div class="container-fluid pt-4 px-4">
    <div class="container-fluid pt-4 px-4">
        
    </div>
        <?php
        if ($commandResult->num_rows > 0) {
            while ($commandRow = $commandResult->fetch_assoc()) {
                $commandId = $commandRow['Id_Command'];
                $userId = $commandRow['Id_user'];

                // Fetch user's details
                $userQuery = "SELECT * FROM users WHERE Id_user = $userId ";
                $userResult = mysqli_query($conn, $userQuery);
                $userRow = mysqli_fetch_assoc($userResult);
                $userName = $userRow['First_Name'] . " " . $userRow['Last_Name'];

                // Fetch commands details including address and discount
                $commandDetailsQuery = "SELECT command.*, medicament.Name_Med, categorie.Category_Name, medicament.Price, medicament.quant, subcommand.qnt, medicament.id_pharmacy, users.addres, command.discount
                    FROM command 
                    INNER JOIN subcommand ON command.Id_Command = subcommand.comndID
                    INNER JOIN medicament ON subcommand.midID = medicament.Id_Medicament
                    INNER JOIN categorie ON medicament.Category_Id = categorie.Id_Category
                    INNER JOIN users ON command.Id_user = users.Id_user
                    WHERE command.Id_user = $userId AND command.Id_Command=$commandId";

                $subCommandResult = mysqli_query($conn, $commandDetailsQuery); // Fetch subcommands for each command
                
                // Displaying address and discount
                $address = $userRow['addres'];;
                $phonenumber = $userRow['phonenumber'];;
                $discount = $commandRow['discount']*100;
                $commandId = $commandRow['Id_Command'];

                // Fetch all subcommands for this command ID
                $subCommandTotalQuery = "SELECT SUM(m.Price * s.qnt) AS total_price
                                        FROM subcommand s
                                        INNER JOIN medicament m ON s.midID = m.Id_Medicament
                                        WHERE s.comndID = $commandId";
                $subCommandTotalResult = mysqli_query($conn, $subCommandTotalQuery);
                $subCommandTotalRow = mysqli_fetch_assoc($subCommandTotalResult);
                $totalPrice = $subCommandTotalRow['total_price'];

                // Calculate total price after discount
                $totalPriceAfterDiscount = $totalPrice * (1 - $commandRow['discount']);

        ?>
        
                <div class="bg-light text-center rounded p-4 mb-4">
                    <h6 class="mb-0"><?php echo $userName ?>commandes de</h6>
                    <h6 class="mb-0">Address: <?php echo $address; ?></h6>
                    <h6 class="mb-0">Numéro de téléphone: <?php echo $phonenumber; ?></h6>
                    <h6 class="mb-0">remise: <?php echo $discount; ?>%</h6>
                    <h6 class="mb-0">prix total: <?php echo $totalPriceAfterDiscount; ?></h6>
                    <?php if ($commandRow['statusCommand'] == 1) { ?>
                        <h6 class="mb-0">Confirmé</h6>
                    <?php } ?>
                    <?php if ($commandRow['statusCommand'] == 0) { ?>
                        <!-- Display confirm button if status is 0 -->
                        <form action="" method="POST">
                            <input type="hidden" name="command_id" value="<?php echo $commandId; ?>">
                            <button type="submit" name="confirm_command" class="btn btn-success mt-2">Confirmer la Commande</button>
                        </form>
                    <?php } ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Nom du produit</th>
                                    <th scope="col">Catégorie</th>
                                    <th scope="col">Prix</th>
                                    <th scope="col">quantité commandée</th>
                                    <th scope="col">Quantité disponible</th>
                                    <th scope="col">Pharmacie</th>
                                    <th scope="col">Date de commande</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($subCommandResult->num_rows > 0) {
                                    while ($subCommandRow = $subCommandResult->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $subCommandRow['Name_Med'] . "</td>";
                                        echo "<td>" . $subCommandRow['Category_Name'] . "</td>";
                                        echo "<td>" . $subCommandRow['Price'] . "</td>";
                                        echo "<td>" . $subCommandRow['qnt'] . "</td>";
                                        if ($subCommandRow['quant'] > 0) {
                                            echo "<td>" . $subCommandRow['quant'] . "</td>";
                                        } else {
                                            echo "<td>out of stock</td>";
                                        }
                                        // Fetch pharmacy name based on pharmacy ID associated with medication
                                        $pharmacyId = $subCommandRow['id_pharmacy'];
                                        $pharmacyQuery = "SELECT name FROM pharmacy WHERE id = $pharmacyId";
                                        $pharmacyResult = mysqli_query($conn, $pharmacyQuery);
                                        $pharmacyRow = mysqli_fetch_assoc($pharmacyResult);
                                        $pharmacyName = $pharmacyRow['name'];
                                        echo "<td>" . $pharmacyName . "</td>";
                                        echo "<td>" . $commandRow['dateCommand'] . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No commands found for this user</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

        <?php
            }
        } else {
            echo "<p>No commands found</p>";
        }
        ?>
    </div>

</div>
<!-- Content End -->

<?php 
include_once('include/footer.php');
?>
