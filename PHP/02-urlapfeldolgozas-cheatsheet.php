<?php
// ============================================
// PHP ŰRLAPFELDOLGOZÁS
// ============================================

// ============ ŰRLAP ADATOK FOGADÁSA ============

// --- GET metódus ---
// URL: example.php?name=John&age=25

// Egyedi paraméter
$name = $_GET['name'] ?? ''; // 'John'
$age = $_GET['age'] ?? 0; // 25

// Létezés ellenőrzés
if (isset($_GET['name'])) {
    echo "Name: " . $_GET['name'];
}

// --- POST metódus ---
// Űrlap küldés után:
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// --- REQUEST (GET vagy POST) ---
$value = $_REQUEST['field'] ?? '';

// ============ ŰRLAP VALIDÁCIÓ ============

$errors = [];

// Kötelező mező
if (empty($_POST['name'])) {
    $errors['name'] = "Name is required!";
}

// Minimum hossz
if (strlen($_POST['name']) < 3) {
    $errors['name'] = "Name must be at least 3 characters!";
}

// Maximum hossz
if (strlen($_POST['name']) > 50) {
    $errors['name'] = "Name must be at most 50 characters!";
}

// Email validáció
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format!";
}

// Számérték validáció
if (!is_numeric($_POST['age'])) {
    $errors['age'] = "Age must be a number!";
}

// Tartomány ellenőrzés
$age = intval($_POST['age']);
if ($age < 18 || $age > 100) {
    $errors['age'] = "Age must be between 18 and 100!";
}

// Jelszó erősség
if (strlen($_POST['password']) < 8) {
    $errors['password'] = "Password must be at least 8 characters!";
}

// Jelszó megerősítés
if ($_POST['password'] !== $_POST['password_confirm']) {
    $errors['password_confirm'] = "Passwords do not match!";
}

// URL validáció
if (!filter_var($_POST['website'], FILTER_VALIDATE_URL)) {
    $errors['website'] = "Invalid URL!";
}

// Regex validáció (pl. telefonszám)
if (!preg_match('/^\+?[0-9]{9,15}$/', $_POST['phone'])) {
    $errors['phone'] = "Invalid phone number!";
}

// ============ BIZTONSÁG ============

// --- XSS védelem ---
// Speciális karakterek escape-elése HTML kimenetben
$safeName = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
echo "<p>Hello $safeName</p>";

// --- CSRF védelem ---
// Token generálás
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Token ellenőrzés
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }
}

// --- SQL Injection védelem ---
// Prepared statement használata (lásd adattárolás cheatsheet)

// --- Input sanitizálás ---
$clean = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$cleanEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$cleanUrl = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_URL);
$cleanInt = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);

// Több mező sanitizálása egyszerre
$filters = [
    'name' => FILTER_SANITIZE_STRING,
    'email' => FILTER_SANITIZE_EMAIL,
    'age' => FILTER_SANITIZE_NUMBER_INT
];
$cleanData = filter_input_array(INPUT_POST, $filters);

// ============ CHECKBOX ÉS RADIO ============

// Checkbox (egyszerű)
$subscribe = isset($_POST['subscribe']); // true ha be van pipálva

// Checkbox (érték)
$agreeTerms = isset($_POST['terms']) && $_POST['terms'] === 'yes';

// Checkbox csoport (több érték)
// <input type="checkbox" name="interests[]" value="sports">
// <input type="checkbox" name="interests[]" value="music">
$interests = $_POST['interests'] ?? [];
// $interests = ["sports", "music"]

// Radio button
// <input type="radio" name="gender" value="male">
// <input type="radio" name="gender" value="female">
$gender = $_POST['gender'] ?? '';

// ============ SELECT (DROPDOWN) ============

// Egyszerű select
// <select name="country">
$country = $_POST['country'] ?? '';

// Multiple select
// <select name="colors[]" multiple>
$colors = $_POST['colors'] ?? [];

// ============ FILE UPLOAD ============

// Fájl feltöltés ellenőrzés
if (isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];

    // Fájl információk
    $fileName = $file['name']; // eredeti név
    $fileTmpName = $file['tmp_name']; // ideiglenes hely
    $fileSize = $file['size']; // méret bájtban
    $fileError = $file['error']; // hiba kód
    $fileType = $file['type']; // MIME típus

    // Hiba ellenőrzés
    if ($fileError === UPLOAD_ERR_OK) {
        // Méret ellenőrzés (pl. max 5MB)
        if ($fileSize > 5 * 1024 * 1024) {
            $errors['avatar'] = "File is too large! Max 5MB.";
        }

        // Kiterjesztés ellenőrzés
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowedExts)) {
            $errors['avatar'] = "Invalid file type! Allowed: jpg, jpeg, png, gif";
        }

        // MIME type ellenőrzés
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            $errors['avatar'] = "Invalid file type!";
        }

        // Fájl mozgatása
        if (empty($errors)) {
            $uploadDir = 'uploads/';
            $newFileName = uniqid() . '_' . $fileName;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpName, $destination)) {
                echo "File uploaded successfully!";
            } else {
                $errors['avatar'] = "Failed to upload file!";
            }
        }
    } else {
        $errors['avatar'] = "Upload error: " . $fileError;
    }
}

// Több fájl feltöltése
// <input type="file" name="photos[]" multiple>
if (isset($_FILES['photos'])) {
    $fileCount = count($_FILES['photos']['name']);

    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = $_FILES['photos']['name'][$i];
        $fileTmpName = $_FILES['photos']['tmp_name'][$i];
        $fileError = $_FILES['photos']['error'][$i];

        if ($fileError === UPLOAD_ERR_OK) {
            $destination = 'uploads/' . uniqid() . '_' . $fileName;
            move_uploaded_file($fileTmpName, $destination);
        }
    }
}

// ============ ŰRLAP PÉLDA - TELJES FELDOLGOZÁS ============

// Űrlap állapot
$submitted = false;
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF védelem
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request!");
    }

    // Adatok tisztítása
    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'age' => intval($_POST['age'] ?? 0),
        'gender' => $_POST['gender'] ?? '',
        'interests' => $_POST['interests'] ?? [],
        'message' => trim($_POST['message'] ?? '')
    ];

    // Validáció
    if (empty($formData['name'])) {
        $errors['name'] = "Name is required!";
    } elseif (strlen($formData['name']) < 3) {
        $errors['name'] = "Name must be at least 3 characters!";
    }

    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email!";
    }

    if ($formData['age'] < 18) {
        $errors['age'] = "You must be at least 18 years old!";
    }

    if (empty($formData['gender'])) {
        $errors['gender'] = "Please select gender!";
    }

    // Ha nincs hiba, feldolgozás
    if (empty($errors)) {
        // Adatok mentése, email küldés, stb.
        $submitted = true;

        // Adatok mentése fájlba vagy adatbázisba
        // ...

        // Átirányítás sikeres küldés után (POST/REDIRECT/GET pattern)
        header('Location: success.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Űrlap példa</title>
    <style>
        .error { color: red; font-size: 0.9em; }
        .success { color: green; padding: 10px; background: #d4edda; }
        input.invalid { border: 2px solid red; }
    </style>
</head>
<body>

<?php if ($submitted): ?>
    <div class="success">Form submitted successfully!</div>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <!-- CSRF token -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <!-- Name -->
    <div>
        <label for="name">Name:</label>
        <input
            type="text"
            id="name"
            name="name"
            value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
            class="<?= isset($errors['name']) ? 'invalid' : '' ?>"
        >
        <?php if (isset($errors['name'])): ?>
            <span class="error"><?= $errors['name'] ?></span>
        <?php endif; ?>
    </div>

    <!-- Email -->
    <div>
        <label for="email">Email:</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
        >
        <?php if (isset($errors['email'])): ?>
            <span class="error"><?= $errors['email'] ?></span>
        <?php endif; ?>
    </div>

    <!-- Age -->
    <div>
        <label for="age">Age:</label>
        <input
            type="number"
            id="age"
            name="age"
            value="<?= $formData['age'] ?? '' ?>"
        >
        <?php if (isset($errors['age'])): ?>
            <span class="error"><?= $errors['age'] ?></span>
        <?php endif; ?>
    </div>

    <!-- Gender (radio) -->
    <div>
        <label>Gender:</label>
        <label>
            <input
                type="radio"
                name="gender"
                value="male"
                <?= ($formData['gender'] ?? '') === 'male' ? 'checked' : '' ?>
            > Male
        </label>
        <label>
            <input
                type="radio"
                name="gender"
                value="female"
                <?= ($formData['gender'] ?? '') === 'female' ? 'checked' : '' ?>
            > Female
        </label>
        <?php if (isset($errors['gender'])): ?>
            <span class="error"><?= $errors['gender'] ?></span>
        <?php endif; ?>
    </div>

    <!-- Interests (checkbox group) -->
    <div>
        <label>Interests:</label>
        <?php
        $interestOptions = ['sports', 'music', 'reading', 'travel'];
        $selectedInterests = $formData['interests'] ?? [];
        ?>
        <?php foreach ($interestOptions as $interest): ?>
            <label>
                <input
                    type="checkbox"
                    name="interests[]"
                    value="<?= $interest ?>"
                    <?= in_array($interest, $selectedInterests) ? 'checked' : '' ?>
                > <?= ucfirst($interest) ?>
            </label>
        <?php endforeach; ?>
    </div>

    <!-- Message (textarea) -->
    <div>
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5"><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
    </div>

    <!-- File upload -->
    <div>
        <label for="avatar">Avatar:</label>
        <input type="file" id="avatar" name="avatar">
        <?php if (isset($errors['avatar'])): ?>
            <span class="error"><?= $errors['avatar'] ?></span>
        <?php endif; ?>
    </div>

    <!-- Submit -->
    <button type="submit">Submit</button>
</form>

</body>
</html>

<?php

// ============ FUNKCIONÁLIS MEGKÖZELÍTÉS ============

// Validátor függvények
$validators = [
    'name' => [
        fn($v) => !empty($v) ? null : "Name is required!",
        fn($v) => strlen($v) >= 3 ? null : "Name must be at least 3 characters!",
    ],
    'email' => [
        fn($v) => filter_var($v, FILTER_VALIDATE_EMAIL) ? null : "Invalid email!",
    ],
    'age' => [
        fn($v) => is_numeric($v) ? null : "Age must be a number!",
        fn($v) => intval($v) >= 18 ? null : "You must be at least 18!",
    ]
];

// Validáció futtatása
function validateField($field, $value, $validators) {
    $errors = [];
    foreach ($validators as $validator) {
        $error = $validator($value);
        if ($error !== null) {
            $errors[] = $error;
        }
    }
    return $errors;
}

// Összes mező validálása
$allErrors = [];
foreach ($_POST as $field => $value) {
    if (isset($validators[$field])) {
        $fieldErrors = validateField($field, $value, $validators[$field]);
        if (!empty($fieldErrors)) {
            $allErrors[$field] = $fieldErrors;
        }
    }
}

// ============ SEGÉD FÜGGVÉNYEK ============

// Űrlap érték visszatöltése
function getValue($field, $default = '') {
    return htmlspecialchars($_POST[$field] ?? $default);
}

// Checkbox checked state
function isChecked($field, $value) {
    return isset($_POST[$field]) &&
           (is_array($_POST[$field]) ? in_array($value, $_POST[$field]) : $_POST[$field] === $value);
}

// Select option selected state
function isSelected($field, $value) {
    return isset($_POST[$field]) && $_POST[$field] === $value;
}

// Hibaüzenet megjelenítés
function showError($field, $errors) {
    if (isset($errors[$field])) {
        echo '<span class="error">' . htmlspecialchars($errors[$field]) . '</span>';
    }
}

?>
