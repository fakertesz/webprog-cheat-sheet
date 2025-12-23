<?php
require_once 'storage.php';

// g. Load log by ID
$logId = $_GET['id'] ?? null;

$logStorage = new Storage(new JsonIO('logs.json'));
$log = $logStorage->findById($logId);

$trackStorage = new Storage(new JsonIO('tracks.json'));
$track = $trackStorage->findById($log['track_id']);
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
  <a href="index.php">Back to main page</a>
  <h2>Log details</h2>
  <a href="edit.php?id=<?= $logId ?>">Edit log</a>
  <dl>
    <dt>Track</dt>
    <dd><?= $track['id'] ?>. <?= $track['from'] ?> - <?= $track['to'] ?></dd>

    <dt>Date</dt>
    <dd><?= $log['date_from'] ?> - <?= $log['date_to'] ?></dd>

    <dt>Log</dt>
    <dd><?= nl2br(htmlspecialchars($log['log'])) ?></dd>

    <dt>Fellows</dt>
    <dd><?= implode(', ', $log['fellows']) ?></dd>

    <dt>Rating</dt>
    <dd><?= $log['rating'] ?></dd>
  </dl>

</body>
</html>