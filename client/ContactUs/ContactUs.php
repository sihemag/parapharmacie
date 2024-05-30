<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ContactUs.css">
    <title>Contact Us</title>
</head>
<body>
    <?php include("../header/header.php"); ?>

    <div class="containerr">
        <h1>Contacter nous</h1>
        <p>Vous avez des questions ? Nous aimerions recevoir de vos nouvelles. Envoyez-nous un message et nous vous répondrons dans les plus brefs délais.</p>

        <form action="send_email.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>

            <button type="submit">Envoyez le message</button>
        </form>
    </div>

    <?php include("../footer/footer.php"); ?>
</body>
</html>
