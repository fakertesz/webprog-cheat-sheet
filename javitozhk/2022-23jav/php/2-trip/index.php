<?php
	$places = [
		'Írottkő',
		'Sárvár',
		'Sümeg',
		'Keszthely',
		'Tapolca',
		'Badacsonytördemic',
		'Nagyvázsony',
		'Városlőd',
		'Zirc',
		'Bodajk',
		'Szárliget',
		'Dorog',
		'Piliscsaba',
		'Hűvösvölgy',
		'Rozália téglagyár',
		'Dobogókő',
		'Visegrád',
		'Nagymaros',
		'Nógrád',
		'Becske',
		'Mátraverebély',
		'Mátraháza',
		'Sirok',
		'Szarvaskő',
		'Putnok',
		'Aggtelek',
		'Bódvaszilas',
		'Boldogkőváralja',
		'Sátoraljaújhely',
		'Hollóháza'
	];

	// Validation
	$errors = [];
	$success = false;

	if (count($_GET) > 0) {
		// a. Validate trackname
		if (!isset($_GET['trackname']) || trim($_GET['trackname']) === '') {
			$errors['trackname'] = 'Track name is required!';
		}

		// a. Validate distance
		if (!isset($_GET['distance']) || trim($_GET['distance']) === '') {
			$errors['distance'] = 'Distance is required!';
		} elseif (!is_numeric($_GET['distance'])) {
			$errors['distance'] = 'Distance must be a number!';
		}

		// b. Validate from
		if (!isset($_GET['from']) || trim($_GET['from']) === '') {
			$errors['from'] = 'Starting point is required!';
		} elseif (!in_array($_GET['from'], $places)) {
			$errors['from'] = 'Invalid starting point!';
		}

		// b. Validate to
		if (!isset($_GET['to']) || trim($_GET['to']) === '') {
			$errors['to'] = 'Destination is required!';
		} elseif (!in_array($_GET['to'], $places)) {
			$errors['to'] = 'Invalid destination!';
		} elseif (isset($_GET['from']) && $_GET['from'] === $_GET['to']) {
			$errors['to'] = 'Destination must be different from starting point!';
		}

		// c. Validate time
		if (!isset($_GET['time']) || trim($_GET['time']) === '') {
			$errors['time'] = 'Time is required!';
		} elseif (!preg_match('/^\d:\d{2}$/', $_GET['time'])) {
			$errors['time'] = 'Time must be in X:XX format!';
		} else {
			$parts = explode(':', $_GET['time']);
			$hours = (int)$parts[0];
			$minutes = (int)$parts[1];

			if ($hours > 7) {
				$errors['time'] = 'Hours cannot be greater than 7!';
			}
			if ($minutes >= 60) {
				$errors['time'] = 'Minutes must be less than 60!';
			}
		}

		// f. Check if validation passed
		if (count($errors) === 0) {
			$success = true;
		}
	}
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
    <h1>Task 2: Trip registration</h1>
    <form action="index.php" method="get" novalidate>
        <label for="i1">Track name:</label>
        <input type="text" name="trackname" id="i1" value="<?= htmlspecialchars($_GET['trackname'] ?? '') ?>">
        <?php if (isset($errors['trackname'])): ?>
            <span class="error"><?= $errors['trackname'] ?></span>
        <?php endif; ?>
        <br>

        <label for="i2">From:</label>
        <input type="text" name="from" id="i2" value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
        <?php if (isset($errors['from'])): ?>
            <span class="error"><?= $errors['from'] ?></span>
        <?php endif; ?>
        <br>

		<label for="i3">To:</label>
        <input type="text" name="to" id="i3" value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
        <?php if (isset($errors['to'])): ?>
            <span class="error"><?= $errors['to'] ?></span>
        <?php endif; ?>
        <br>

        <label for="i4">Distance:</label>
        <input type="text" name="distance" id="i4" value="<?= htmlspecialchars($_GET['distance'] ?? '') ?>">
        <?php if (isset($errors['distance'])): ?>
            <span class="error"><?= $errors['distance'] ?></span>
        <?php endif; ?>
        <br>

		<label for="i5">Time:</label>
        <input type="text" name="time" id="i5" value="<?= htmlspecialchars($_GET['time'] ?? '') ?>">
        <?php if (isset($errors['time'])): ?>
            <span class="error"><?= $errors['time'] ?></span>
        <?php endif; ?>
        <br>

		<button type="submit">Register</button>
    </form>

<?php if ($success): ?>
    <div id="success">
        <h2>Trip data saved!</h2>
	</div>
<?php endif; ?>

    <h2>Hyperlinks for testing</h2>
    <a href="index.php?trackname=&from=&to=&distance=&time=">trackname=&from=&to=&distance=&time=</a><br>
    <a href="index.php?trackname=10.sz.+túra&from=&to=&distance=&time=">trackname=10.sz.+túra&from=&to=&distance=&time=</a><br>
    <a href="index.php?trackname=10.sz.+túra&from=Budapest&to=&distance=&time=">trackname=10.sz.+túra&from=Budapest&to=&distance=&time=</a><br>
    <a href="index.php?trackname=10.sz.+túra&from=Sárvár&to=&distance=&time=">trackname=10.sz.+túra&from=Sárvár&to=&distance=&time=</a><br>
	<a href="index.php?trackname=10.sz.+túra&from=Sárvár&to=Sárvár&distance=&time=">trackname=10.sz.+túra&from=Sárvár&to=Sárvár&distance=&time=</a><br>
    <a href="index.php?trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=&time=">trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=&time=</a><br>
    <a href="index.php?trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=ezer&time=">trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=ezer&time=</a><br>
    <a href="index.php?trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=1000&time=">trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=1000&time=</a><br>
    <a href="index.php?trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=1000&time=10">trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=1000&time=10</a><br>
    <a href="index.php?trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=1000&time=10%3A60">trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=1000&time=10%3A60</a><br>
    <a href="index.php?trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=1000&time=4%3A10"><span style="color: green">Correct input: </span>trackname=10.sz.+túra&from=Sárvár&to=Dobogókő&distance=1000&time=4%3A10</a><br>

    </body>
</html>
