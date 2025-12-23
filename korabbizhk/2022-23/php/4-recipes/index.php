<?php
session_start();

// b. Hűtő inicializálása, ha nincs még
if (!isset($_SESSION['fridge'])) {
    $_SESSION['fridge'] = [];
}

// Receptek betöltése
$recipes = json_decode(file_get_contents('recipes.json'), true);

// f. Recept elkészítése - hozzávalók eltávolítása a hűtőből
if (isset($_GET['cook'])) {
    $recipeName = $_GET['cook'];
    if (isset($recipes[$recipeName])) {
        $ingredients = $recipes[$recipeName];
        foreach ($ingredients as $ingredient) {
            $key = array_search($ingredient, $_SESSION['fridge']);
            if ($key !== false) {
                unset($_SESSION['fridge'][$key]);
            }
        }
        $_SESSION['fridge'] = array_values($_SESSION['fridge']); // Re-index
    }
    header('Location: index.php');
    exit();
}

// e. Ellenőrizzük, mely receptek készíthetőek el
function canCook($recipeName, $ingredients, $fridge) {
    foreach ($ingredients as $ingredient) {
        if (!in_array($ingredient, $fridge)) {
            return false;
        }
    }
    return true;
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

    <!-- a. Receptek listája linkekkel -->
    <h2>List of recipes</h2>
    <ul>
        <?php foreach ($recipes as $name => $ingredients): ?>
            <li>
                <a href="details.php?recipe=<?php echo urlencode($name) ?>">
                    <?php echo $name ?>
                </a>
                <!-- e. Elkészíthető jelzés -->
                <?php if (canCook($name, $ingredients, $_SESSION['fridge'])): ?>
                    - <strong>Elkészíthető!</strong>
                    <!-- f. Elkészítés gomb -->
                    <a href="index.php?cook=<?php echo urlencode($name) ?>">[Cook it!]</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- b. Hűtő tartalmának megjelenítése -->
    <h2>Fridge contents</h2>
    <?php if (empty($_SESSION['fridge'])): ?>
        <p>The fridge is empty.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($_SESSION['fridge'] as $item): ?>
                <li><?php echo $item ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>