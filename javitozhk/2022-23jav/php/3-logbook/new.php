<?php
require_once 'storage.php';

// a. Load tracks
$trackStorage = new Storage(new JsonIO('tracks.json'));
$tracks = $trackStorage->findAll();

// c, d. Get all unique fellows from existing logs
$logStorage = new Storage(new JsonIO('logs.json'));
$logs = $logStorage->findAll();
$allFellows = [];
foreach ($logs as $log) {
  if (isset($log['fellows']) && is_array($log['fellows'])) {
    $allFellows = array_merge($allFellows, $log['fellows']);
  }
}
$allFellows = array_unique($allFellows);

// b. Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $trackId = $_POST['track'];
  $dateFrom = $_POST['date-from'];
  $dateTo = $_POST['date-to'];
  $logText = $_POST['log'];
  $rating = $_POST['rating'];

  // d. Combine fellows from checkboxes and text input
  $fellows = $_POST['fellows'] ?? [];

  if (!empty($_POST['fellow-text'])) {
    $textFellows = explode(',', $_POST['fellow-text']);
    $textFellows = array_map('trim', $textFellows);
    $textFellows = array_filter($textFellows); // Remove empty strings
    $fellows = array_merge($fellows, $textFellows);
  }

  $newLog = [
    'track_id' => $trackId,
    'date_from' => $dateFrom,
    'date_to' => $dateTo,
    'log' => $logText,
    'fellows' => $fellows,
    'rating' => $rating
  ];

  $logStorage->add($newLog);

  header('Location: index.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task 3</title>
  <link rel="stylesheet" href="index.css">
</head>
<body>
  <h1>Task 3: Logbook</h1>
  <h2>New log</h2>
  <form action="" method="post">
    Track: <br>
    <select name="track" required>
      <?php foreach ($tracks as $track): ?>
        <option value="<?= $track['id'] ?>"><?= $track['id'] ?>. <?= $track['from'] ?> - <?= $track['to'] ?></option>
      <?php endforeach; ?>
    </select> <br>
    Date interval: <br>
    <input type="date" name="date-from" required> - <input type="date" name="date-to" required> <br>
    Log: <br>
    <textarea name="log" cols="120" rows="10" placeholder="Write your experiences here..." required></textarea> <br>
    Fellows: <br>
    <?php foreach ($allFellows as $fellow): ?>
      <input type="checkbox" name="fellows[]" value="<?= htmlspecialchars($fellow) ?>"> <?= htmlspecialchars($fellow) ?> <br>
    <?php endforeach; ?>
    <small>Add new fellows as a comma-separated list:</small> <br>
    <textarea name="fellow-text" cols="20" rows="8" placeholder="fellow1,fellow2"></textarea> <br>
    Rating: <br>
    <input type="radio" name="rating" value="1" required> 1
    <input type="radio" name="rating" value="2"> 2
    <input type="radio" name="rating" value="3"> 3
    <input type="radio" name="rating" value="4"> 4
    <input type="radio" name="rating" value="5"> 5
    <br>
    <button type="submit">Add new track</button>
  </form>
</body>
</html>