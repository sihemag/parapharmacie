<?php
include_once('../config/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="RecentSalesStyles.css.css">
    <title>Recent Sales</title>
</head>

<body>
    <header>
        <h1>Recent Sales</h1>

    </header>

    <section class="products-table">

        <table>
            <thead>
                <tr>
                    <th scope="col">name & last name</th>
                    <th scope="col">Rank</th>
                    <th scope="col">classification</th>
                    <th scope="col">N.S</th>
                    <th scope="col">Class</th>
                    <th scope="col">N.J</th>
                    <th scope="col">M.N</th>
                    <th scope="col">base salary</th>
                    <th scope="col">Experience compensation</th>
                    <th scope="col">Basic salary</th>
                    <th scope="col">authorization grant 25%</th>
                    <th scope="col">curling grant 20%</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                            $sql = "SELECT * FROM main";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $rowNumber = 0;
                                while ($row = $result->fetch_assoc()) {
                                    $rowNumber++;
                                    $rowColor = $rowNumber % 2 === 0 ? '#cdcdcd' : '#ffffff';
                                    echo "<tr style='background-color:$rowColor'>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['Rank'] . "</td>";
                                    echo "<td>" . $row['classification'] . "</td>";
                                    echo "<td>" . $row['N.S'] . "</td>";
                                    echo "<td>" . $row['Class'] . "</td>";
                                    echo "<td>" . $row['N.J'] . "</td>";
                                    echo "<td>" . $row['M.N'] . "</td>";
                                    echo "<td>" . $row['base_salary'] . "</td>";
                                    echo "<td>" . $row['Experience_compensation'] . "</td>";
                                    echo "<td>" . $row['Basic_salary'] . "</td>";
                                    echo "<td>";
                                    echo $row['authorization_grant'] == 0 ? "-" : $row['authorization_grant'];
                                    echo "</td>";

                                    echo "<td>";
                                    echo $row['curling_grant'] == 0 ? "-" : $row['curling_grant'];
                                    echo "</td>";
                                }
                            } else {
                                echo "<tr><td colspan='12'>No data found</td></tr>";
                            }
                        ?>
                    </tbody>
        </table>

    </section>
</body>

</html>