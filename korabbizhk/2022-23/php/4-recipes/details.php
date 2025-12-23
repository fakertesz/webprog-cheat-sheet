<?php
session_start();

// Hűtő inicializálása
if (!isset($_SESSION['fridge'])) {
    $_SESSION['fridge'] = [];
}

// Receptek betöltése
$recipes = json_decode(file_get_contents('recipes.json'), true);

// Kiválasztott recept lekérése
$recipeName = $_GET['recipe'] ?? null;

if (!$recipeName || !isset($recipes[$recipeName])) {
    header('Location: index.php');
    exit();
}

$ingredients = $recipes[$recipeName];

// d. Űrlap feldolgozása - új hozzávalók hozzáadása a hűtőhöz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Megnézzük, mely hozzávalók vannak bejelölve ÉS még NEM voltak a hűtőben
    foreach ($ingredients as $ingredient) {
        $isChecked = isset($_POST['ingredient_' . $ingredient]);
        $wasInFridge = in_array($ingredient, $_SESSION['fridge']);

        // Csak akkor adjuk hozzá, ha be van jelölve ÉS még nincs a hűtőben
        if ($isChecked && !$wasInFridge) {
            $_SESSION['fridge'][] = $ingredient;
        }
    }

    header('Location: details.php?recipe=' . urlencode($recipeName));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Task 4</title>
</head>
<body>
    <h1>Task 4: Recipe tracker</h1>

    <a href="index.php">← Back to recipes</a>

    <!-- c. Recept neve -->
    <h2><?php echo $recipeName ?></h2>

    <!-- c. d. Hozzávalók listája jelölőmezőkkel -->
    <form action="" method="POST">
        <?php foreach ($ingredients as $ingredient): ?>
            <?php $inFridge = in_array($ingredient, $_SESSION['fridge']); ?>

            <input
                type="checkbox"
                id="ingredient_<?php echo $ingredient ?>"
                name="ingredient_<?php echo $ingredient ?>"
                <?php echo $inFridge ? 'checked disabled' : '' ?>
            >
            <label for="ingredient_<?php echo $ingredient ?>">
                <?php echo $ingredient ?>
            </label>
            <br>
        <?php endforeach; ?>

        <br>
        <button type="submit">Save</button>
    </form>

    </body>
</html>