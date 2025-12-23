<?php
$errors = [];
$isSubmitted = count($_GET) > 0;

// a. Fullname validáció: kötelező, legalább két szóból álljon
if ($isSubmitted) {
    if (empty($_GET['fullname'])) {
        $errors['fullname'] = "Name is required!";
    } elseif (str_word_count(trim($_GET['fullname'])) < 2) {
        $errors['fullname'] = "Name must contain at least two words!";
    }

    // b. Email validáció: kötelező, formailag helyes e-mail cím
    if (empty($_GET['email'])) {
        $errors['email'] = "Email is required!";
    } elseif (!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email must be a valid email address!";
    }

    // c. Colour validáció: kötelező, csak megadott értékek
    $validColours = ['black', 'white', 'gold', 'pink', 'blue'];
    if (empty($_GET['colour'])) {
        $errors['colour'] = "Choose a colour!";
    } elseif (!in_array($_GET['colour'], $validColours)) {
        $errors['colour'] = "Colour must be one of: black, white, gold, pink, blue!";
    }

    // d. Cardnumber validáció: kötelező, pontosan 19 karakter
    if (empty($_GET['cardnumber'])) {
        $errors['cardnumber'] = "Card number is required!";
    } elseif (strlen($_GET['cardnumber']) != 19) {
        $errors['cardnumber'] = "Card number must be exactly 19 characters long!";
    }
    // e. Cardnumber formátum validáció: XXXX-XXXX-XXXX-XXXX
    elseif (!preg_match('/^\d{4}-\d{4}-\d{4}-\d{4}$/', $_GET['cardnumber'])) {
        $errors['cardnumber'] = "Card number must be in format XXXX-XXXX-XXXX-XXXX!";
    }
}

// h. Success - csak akkor, ha elküldte az űrlapot és nincs hiba
$showSuccess = $isSubmitted && empty($errors);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Task 2</title>
</head>
<body>
    <h1>Task 2: You've just won a new phone!</h1>

    <form action="index.php" method="get" novalidate>
        <label for="i1">Your full name:</label>
        <input type="text" name="fullname" id="i1" value="<?= htmlspecialchars($_GET['fullname'] ?? '') ?>">
        <?php if (isset($errors['fullname'])): ?>
            <span style="color: red;"><?= $errors['fullname'] ?></span>
        <?php endif; ?>
        <br>

        <label for="i2">Your e-mail address:</label>
        <input type="text" name="email" id="i2" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
        <?php if (isset($errors['email'])): ?>
            <span style="color: red;"><?= $errors['email'] ?></span>
        <?php endif; ?>
        <br>

        <label>Choose colour:</label>
        <?php if (isset($errors['colour'])): ?>
            <span style="color: red;"><?= $errors['colour'] ?></span>
        <?php endif; ?>
        <br>
        <input type="radio" value="black" name="colour" id="i3a" <?= (($_GET['colour'] ?? '') == 'black') ? 'checked' : '' ?>> <label for="i3a">black</label><br>
        <input type="radio" value="white" name="colour" id="i3b" <?= (($_GET['colour'] ?? '') == 'white') ? 'checked' : '' ?>> <label for="i3b">white</label><br>
        <input type="radio" value="gold" name="colour" id="i3c" <?= (($_GET['colour'] ?? '') == 'gold') ? 'checked' : '' ?>> <label for="i3c">gold</label><br>
        <input type="radio" value="pink" name="colour" id="i3d" <?= (($_GET['colour'] ?? '') == 'pink') ? 'checked' : '' ?>> <label for="i3d">pink</label><br>
        <input type="radio" value="blue" name="colour" id="i3e" <?= (($_GET['colour'] ?? '') == 'blue') ? 'checked' : '' ?>> <label for="i3e">blue</label><br>

        <!-- f. g. Cardnumber mező hibaüzenettel és állapottartás -->
        <label for="i4">Your credit card number:</label>
        <input type="text" name="cardnumber" id="i4" value="<?= htmlspecialchars($_GET['cardnumber'] ?? '') ?>">
        <?php if (isset($errors['cardnumber'])): ?>
            <span style="color: red;"><?= $errors['cardnumber'] ?></span>
        <?php endif; ?>
        <br>

        <button type="submit">Click here to get your free phone today!</button>
    </form>

    <?php if ($showSuccess): ?>
    <div id="success">
        <h2>Congratulations! Your phone is on the way!</h2>
		<div id="progress-bar">
			<div id="progress-bar-fill"></div>
		</div>
	</div>
    <?php endif; ?>

    <h2>Hyperlinks for testing</h2>
    <a href="index.php?fullname=&email=&cardnumber=">fullname=&email=&cardnumber=</a><br>
    <a href="index.php?fullname=Grandma&email=&cardnumber=">fullname=Grandma&email=&cardnumber=</a><br>
    <a href="index.php?fullname=Lovely+Grandma&email=&cardnumber=">fullname=Lovely+Grandma&email=&cardnumber=</a><br>
    <a href="index.php?fullname=Lovely+Grandma&email=nagyi&cardnumber=">fullname=Lovely+Grandma&email=nagyi&cardnumber=</a><br>
    <a href="index.php?fullname=Lovely+Grandma&email=nagyi%40webprog.hu&cardnumber=">fullname=Lovely+Grandma&email=nagyi%40webprog.hu&cardnumber=</a><br>
    <a href="index.php?fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=red&cardnumber=">fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=red&cardnumber=</a><br>
    <a href="index.php?fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=pink&cardnumber=">fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=pink&cardnumber=</a><br>
    <a href="index.php?fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=pink&cardnumber=1234">fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=pink&cardnumber=1234</a><br>
    <a href="index.php?fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=pink&cardnumber=1234.5678.1234.5678">fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=pink&cardnumber=1234.5678.1234.5678</a><br>
    <a href="index.php?fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=pink&cardnumber=1234-5678-1234-5678"><span style="color: green">Correct input: </span>fullname=Lovely+Grandma&email=nagyi%40webprog.hu&colour=pink&cardnumber=1234-5678-1234-5678</a><br>

    <script src="index.js"></script>
    </body>
</html>
