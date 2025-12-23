<?php
require_once 'storage.php';

// Load tracks and logs
$trackStorage = new Storage(new JsonIO('tracks.json'));
$tracks = $trackStorage->findAll();

$logStorage = new Storage(new JsonIO('logs.json'));
$logs = $logStorage->findAll();

// e. Group logs by track
$logsByTrack = [];
foreach ($logs as $log) {
  $trackId = $log['track_id'];
  if (!isset($logsByTrack[$trackId])) {
    $logsByTrack[$trackId] = [];
  }
  $logsByTrack[$trackId][] = $log;
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
  <a href="new.php">Add new log...</a>

  <?php foreach ($tracks as $track): ?>
    <?php
      // f. Skip tracks with no logs
      if (!isset($logsByTrack[$track['id']])) {
        continue;
      }
    ?>
    <h2><?= $track['id'] ?>. <?= $track['from'] ?> - <?= $track['to'] ?></h2>
    <ul>
      <?php foreach ($logsByTrack[$track['id']] as $log): ?>
        <li>
          <a href="log.php?id=<?= $log['id'] ?>"><?= $log['date_from'] ?> - <?= $log['date_to'] ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endforeach; ?>

</body>
</html>