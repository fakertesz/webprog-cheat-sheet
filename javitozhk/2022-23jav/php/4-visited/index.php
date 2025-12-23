<?php
session_start();

$places = [
    "Budapest"          => ["x" => 43, "y" => 38, "next" => ["Cegléd", "Székesfehérvár", "Eger"]],
    "Cegléd"            => ["x" => 55, "y" => 50, "next" => ["Budapest", "Szolnok", "Kecskemét"]],
    "Szolnok"           => ["x" => 60, "y" => 52, "next" => ["Cegléd"]],
    "Eger"              => ["x" => 61, "y" => 25, "next" => ["Budapest", "Miskolc"]],
    "Miskolc"           => ["x" => 67, "y" => 20, "next" => ["Eger", "Nyíregyháza"]],
    "Nyíregyháza"       => ["x" => 81, "y" => 23, "next" => ["Miskolc", "Debrecen"]],
    "Debrecen"          => ["x" => 78, "y" => 38, "next" => ["Nyíregyháza", "Szolnok"]],
    "Kecskemét"         => ["x" => 52, "y" => 60, "next" => ["Cegléd", "Szeged"]],
    "Szeged"            => ["x" => 59, "y" => 79, "next" => ["Kecskemét"]],
    "Tatabánya"         => ["x" => 33, "y" => 33, "next" => ["Budapest", "Győr", "Veszprém"]],
    "Győr"              => ["x" => 22, "y" => 32, "next" => ["Tatabánya", "Sopron", "Szombathely"]],
    "Sopron"            => ["x" => 7,  "y" => 31, "next" => ["Győr", "Szombathely"]],
    "Szombathely"       => ["x" => 8,  "y" => 49, "next" => ["Sopron", "Győr", "Veszprém"]],
    "Székesfehérvár"    => ["x" => 32, "y" => 50, "next" => ["Budapest", "Veszprém"]],
    "Veszprém"          => ["x" => 26, "y" => 52, "next" => ["Székesfehérvár", "Tatabánya", "Szombathely", "Kaposvár"]],
    "Pécs"              => ["x" => 31, "y" => 87, "next" => ["Kaposvár"]],
    "Kaposvár"          => ["x" => 25, "y" => 74, "next" => ["Pécs", "Veszprém"]],
];

// b. Initialize session if empty
if (!isset($_SESSION['current'])) {
    $_SESSION['current'] = 'Budapest';
    $_SESSION['visited'] = [];
    $_SESSION['path'] = ['Budapest'];
}

// d. Handle city click
if (isset($_GET['goto'])) {
    $destination = $_GET['goto'];
    $current = $_SESSION['current'];

    // Check if destination is reachable from current
    if (in_array($destination, $places[$current]['next'])) {
        // Add current to visited if not already there
        if (!in_array($current, $_SESSION['visited'])) {
            $_SESSION['visited'][] = $current;
        }
        // Move to destination
        $_SESSION['current'] = $destination;
        $_SESSION['path'][] = $destination;
    }

    header('Location: index.php');
    exit();
}

$current = $_SESSION['current'];
$visited = $_SESSION['visited'];
$path = $_SESSION['path'];

// f. Check if all cities visited
$allVisited = count($visited) + 1 >= count($places); // +1 for current city
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
    <h1>Task 4: Visited cities</h1>

    <div id="wrapper">
        <div id="map">
            <img src="./hungary.jpg">
        </div>
        <?php foreach ($places as $name => $place): ?>
            <?php
                // Determine color and clickability
                $color = 'lightgray';
                $clickable = false;

                // c. Current city is blue
                if ($name === $current) {
                    $color = 'blue';
                }
                // d. Reachable cities are purple and clickable
                elseif (in_array($name, $places[$current]['next'])) {
                    $color = 'purple';
                    $clickable = true;
                }
                // e. Visited cities are green
                elseif (in_array($name, $visited)) {
                    $color = 'green';
                }
            ?>
            <?php if ($clickable): ?>
                <a href="index.php?goto=<?= urlencode($name) ?>">
                    <div class="pin" style="top: <?= $place['y'] ?>%; left: <?= $place['x'] ?>%; background-color: <?= $color ?>;" title="<?= $name ?>"></div>
                </a>
            <?php else: ?>
                <div class="pin" style="top: <?= $place['y'] ?>%; left: <?= $place['x'] ?>%; background-color: <?= $color ?>;" title="<?= $name ?>"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <b>Path: </b> <?= implode(' → ', $path) ?>

    <?php if ($allVisited): ?>
        <div id="congrats">
            Congratulations, you've visited all major cities in Hungary!
        </div>
    <?php endif; ?>
</body>
</html>
