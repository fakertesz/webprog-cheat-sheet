<?php
require_once 'storage.php';

// h. Load log by ID
$logId = $_GET['id'] ?? null;

$logStorage = new Storage(new JsonIO('logs.json'));
$log = $logStorage->findById($logId);

// Load tracks
$trackStorage = new Storage(new JsonIO('tracks.json'));
$tracks = $trackStorage->findAll();

// Get all unique fellows from existing logs
$logs = $logStorage->findAll();
$allFellows = [];
foreach ($logs as $l) {
  if (isset($l['fellows']) && is_array($l['fellows'])) {
    $allFellows = array_merge($allFellows, $l['fellows']);
  }
}
$allFellows = array_unique($allFellows);

// i. Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $trackId = $_POST['track'];
  $dateFrom = $_POST['date-from'];
  $dateTo = $_POST['date-to'];
  $logText = $_POST['log'];
  $rating = $_POST['rating'];

  // Combine fellows from checkboxes and text input
  $fellows = $_POST['fellows'] ?? [];

  if (!empty($_POST['fellow-text'])) {
    $textFellows = explode(',', $_POST['fellow-text']);
    $textFellows = array_map('trim', $textFellows);
    $textFellows = array_filter($textFellows);
    $fellows = array_merge($fellows, $textFellows);
  }

  $updatedLog = [
    'id' => $logId,
    'track_id' => $trackId,
    'date_from' => $dateFrom,
    'date_to' => $dateTo,
    'log' => $logText,
    'fellows' => $fellows,
    'rating' => $rating
  ];

  $logStorage->update($logId, $updatedLog);

  header('Location: log.php?id=' . $logId);
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
  <h2>Edit log</h2>
  <form action="" method="post">
    Track: <br>
    <select name="track" required>
      <?php foreach ($tracks as $track): ?>
        <option value="<?= $track['id'] ?>" <?= $track['id'] == $log['track_id'] ? 'selected' : '' ?>>
          <?= $track['id'] ?>. <?= $track['from'] ?> - <?= $track['to'] ?>
        </option>
      <?php endforeach; ?>
    </select> <br>
    Date interval: <br>
    <input type="date" name="date-from" value="<?= $log['date_from'] ?>" required> -
    <input type="date" name="date-to" value="<?= $log['date_to'] ?>" required> <br>
    Log: <br>
    <textarea name="log" cols="120" rows="10" placeholder="Write your experiences here..." required><?= htmlspecialchars($log['log']) ?></textarea> <br>
    Fellows: <br>
    <?php foreach ($allFellows as $fellow): ?>
      <input type="checkbox" name="fellows[]" value="<?= htmlspecialchars($fellow) ?>"
        <?= in_array($fellow, $log['fellows']) ? 'checked' : '' ?>>
      <?= htmlspecialchars($fellow) ?> <br>
    <?php endforeach; ?>
    <small>Add new fellows as a comma-separated list:</small> <br>
    <textarea name="fellow-text" cols="20" rows="8" placeholder="fellow1,fellow2"></textarea> <br>
    Rating: <br>
    <?php for ($i = 1; $i <= 5; $i++): ?>
      <input type="radio" name="rating" value="<?= $i ?>" <?= $log['rating'] == $i ? 'checked' : '' ?> required> <?= $i ?>
    <?php endfor; ?>
    <br>
    <button type="submit">Save changes</button>
  </form>
</body>
</html>
