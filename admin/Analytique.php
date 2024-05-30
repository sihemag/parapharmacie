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
        <div class="row">
        <?php
            function generateCard($conn, $bgColor,$Color, $title, $query) {
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $total = $row['total'];
            
                echo '<div class="col-md-3">';
                echo '<div class="bg-'. $bgColor. ' text-start rounded p-4 mb-4 shadow-sm" style="color: white; font-weight: bold;">';
                echo '<h5 style="color: ' . $Color . ';">'. $title. '</h5>';
                echo '<h2 style="color: ' . $Color . ';">'. $total. '</h2>';
                echo '</div>';
                echo '</div>';
            }

            // Usage examples
            generateCard($conn, 'white','black', 'Total Commands', "SELECT COUNT(*) as total FROM command");
            generateCard($conn, 'primary','white', 'Total Users', "SELECT COUNT(*) as total FROM users");
            generateCard($conn, 'warning','white', 'Total Medicament', "SELECT COUNT(*) as total FROM medicament");
            generateCard($conn, 'success','white', 'Total Categories', "SELECT COUNT(*) as total FROM categorie");
        ?>


        </div>

</div>
<!-- Content End -->

<?php 
include_once('include/footer.php');
?>
