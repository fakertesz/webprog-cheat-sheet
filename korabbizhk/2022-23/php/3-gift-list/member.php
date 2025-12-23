<?php
require_once 'storage.php';

// Inicializáljuk a storage-okat
$memberStorage = new Storage(new JsonIO('members.json'));
$ideaStorage = new Storage(new JsonIO('ideas.json'));

// c. Kiválasztott családtag lekérése
$memberId = $_GET['id'] ?? null;
if (!$memberId) {
    header('Location: index.php');
    exit();
}

$member = $memberStorage->findById($memberId);
if (!$member) {
    header('Location: index.php');
    exit();
}

// d. Új ötlet hozzáadása
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['function-add'])) {
    $ideaStorage->add([
        'member_id' => $memberId,
        'name' => $_POST['idea'],
        'active' => true,
        'ready' => false,
        'comments' => []
    ]);
    header("Location: member.php?id=$memberId");
    exit();
}

// f. Új megjegyzés hozzáadása
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-comment'])) {
    $ideaId = $_POST['idea-id'];
    $idea = $ideaStorage->findById($ideaId);

    if ($idea) {
        $idea['comments'][] = $_POST['comment'];
        $ideaStorage->update($ideaId, $idea);
    }

    header("Location: member.php?id=$memberId");
    exit();
}

// g. Ötlet befejezése (complete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete'])) {
    $ideaId = $_POST['idea-id'];
    $idea = $ideaStorage->findById($ideaId);

    if ($idea) {
        $idea['ready'] = true;
        $ideaStorage->update($ideaId, $idea);
    }

    header("Location: member.php?id=$memberId");
    exit();
}

// g. Ötlet elrejtése (hide)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hide'])) {
    $ideaId = $_POST['idea-id'];
    $idea = $ideaStorage->findById($ideaId);

    if ($idea) {
        $idea['active'] = false;
        $ideaStorage->update($ideaId, $idea);
    }

    header("Location: member.php?id=$memberId");
    exit();
}

// e. Aktív ötletek lekérése
$allIdeas = $ideaStorage->findAll(['member_id' => $memberId]);
$activeIdeas = array_filter($allIdeas, fn($idea) => $idea['active']);
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
  <a href="index.php">Back to main page</a>
  <!-- c. Kiválasztott családtag neve -->
  <h2>Ideas for <?php echo $member['name'] ?></h2>

  <!-- d. Új ötlet hozzáadása -->
  <form action="" method="post">
    <fieldset>
      <legend>New idea</legend>
      Idea: <input type="text" name="idea" required>
      <button name="function-add" type="submit">Add new idea</button>
    </fieldset>
  </form>

  <!-- e. Ötletek és megjegyzések listázása (csak aktív ötletek) -->
  <?php foreach ($activeIdeas as $idea): ?>
    <details>
      <summary>
        <?php echo $idea['name'] ?>
        <!-- g. Kész ötlet jelzése pipával -->
        <?php if ($idea['ready']): ?>
          <span>✓</span>
        <?php endif; ?>
      </summary>

      <!-- f. Megjegyzés hozzáadása -->
      <form action="" method="post">
        <input type="hidden" name="idea-id" value="<?php echo $idea['id'] ?>">
        Comment: <input type="text" name="comment" required>
        <button type="submit" name="add-comment">Add comment</button> <br>
      </form>

      <!-- g. Complete és Hide gombok -->
      <form action="" method="post">
        <input type="hidden" name="idea-id" value="<?php echo $idea['id'] ?>">
        <button type="submit" name="complete">Complete</button>
        <button type="submit" name="hide">Hide</button>
      </form>

      <!-- e. Megjegyzések listázása -->
      <ul>
        <?php foreach ($idea['comments'] as $comment): ?>
          <li><?php echo $comment ?></li>
        <?php endforeach; ?>
      </ul>
    </details>
  <?php endforeach; ?>
</body>
</html>