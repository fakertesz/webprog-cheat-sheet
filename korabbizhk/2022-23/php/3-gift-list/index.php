<?php
require_once 'storage.php';

$memberStorage = new Storage(new JsonIO('members.json'));
$ideaStorage = new Storage(new JsonIO('ideas.json'));

// a. Új családtag hozzáadása
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $memberStorage->add([
        'name' => $_POST['name']
    ]);
    header('Location: index.php');
    exit();
}

$members = $memberStorage->findAll();

function getMemberStats($memberId, $ideaStorage) {
    $ideas = $ideaStorage->findAll(['member_id' => $memberId]);
    $activeIdeas = array_filter($ideas, fn($idea) => $idea['active']);
    $readyIdeas = array_filter($activeIdeas, fn($idea) => $idea['ready']);

    return [
        'ready' => count($readyIdeas),
        'total' => count($activeIdeas)
    ];
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
  <h1>Task 3: Gift list</h1>
  <h2>My family members</h2>
  <form action="" method="post">
    Name: <input type="text" name="name" required>
    <button type="submit">Add</button>
  </form>
  <ul>
    <?php foreach ($members as $member): ?>
      <?php $stats = getMemberStats($member['id'], $ideaStorage); ?>
      <li>
        <!-- b. Link a családtaghoz, átadva az ID-t -->
        <a href="member.php?id=<?php echo $member['id'] ?>">
          <?php echo $member['name'] ?>
        </a>
        <!-- h. Statisztikák megjelenítése -->
        (<?php echo $stats['ready'] ?> / <?php echo $stats['total'] ?>)
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>