<?php
// ============================================
// PHP ADATTÁROLÁS (Session, Cookie, JSON)
// ============================================

// ============ FÁJL MŰVELETEK ============

// --- Írás ---
// Teljes fájl írása (felülír)
file_put_contents('data.txt', "Hello World\n");

// Hozzáfűzés
file_put_contents('data.txt', "New line\n", FILE_APPEND);

// Több sor írása
$lines = ["Line 1", "Line 2", "Line 3"];
file_put_contents('data.txt', implode("\n", $lines));

// --- Olvasás ---
// Teljes fájl olvasása stringként
$content = file_get_contents('data.txt');

// Soronkénti olvasás tömbbe
$lines = file('data.txt'); // ["Line 1\n", "Line 2\n", ...]
$linesClean = file('data.txt', FILE_IGNORE_NEW_LINES); // ["Line 1", "Line 2", ...]

// Létezés ellenőrzés
if (file_exists('data.txt')) {
    $content = file_get_contents('data.txt');
}

// Fájl méret
$size = filesize('data.txt'); // bájtban

// --- Fájl műveletek ---
// Törlés
unlink('data.txt');

// Átnevezés / Mozgatás
rename('old.txt', 'new.txt');

// Másolás
copy('source.txt', 'destination.txt');

// Könyvtár műveletek
mkdir('folder'); // létrehozás
rmdir('folder'); // törlés (csak üres könyvtár)
is_dir('folder'); // ellenőrzés

// Fájl lista
$files = scandir('.'); // ['file1.txt', 'file2.txt', ...]
$files = glob('*.txt'); // csak .txt fájlok

// ============ JSON ADATTÁROLÁS ============

// --- JSON írás ---
$data = [
    'users' => [
        ['name' => 'Anna', 'age' => 25],
        ['name' => 'Béla', 'age' => 30]
    ],
    'settings' => [
        'theme' => 'dark',
        'language' => 'hu'
    ]
];

// JSON fájlba mentés
file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

// --- JSON olvasás ---
$jsonContent = file_get_contents('data.json');
$data = json_decode($jsonContent, true); // true = asszociatív tömb

// Hiba ellenőrzés
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON parsing error: " . json_last_error_msg();
}

// --- JSON műveletek ---
// Új elem hozzáadása
$data['users'][] = ['name' => 'Cecília', 'age' => 28];
file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

// Elem módosítása
$data['users'][0]['age'] = 26;
file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

// Elem törlése
unset($data['users'][1]);
$data['users'] = array_values($data['users']); // újra indexelés
file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

// Keresés
$found = array_filter($data['users'], fn($u) => $u['name'] === 'Anna');

// ============ SESSION (MUNKAMENET) ============

// Session indítása (mindig ez az első!)
session_start();

// --- Adatok mentése ---
$_SESSION['user_id'] = 123;
$_SESSION['username'] = 'john_doe';
$_SESSION['is_admin'] = true;
$_SESSION['cart'] = ['item1', 'item2'];

// --- Adatok olvasása ---
$userId = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Guest';

// Létezés ellenőrzés
if (isset($_SESSION['user_id'])) {
    echo "Logged in as: " . $_SESSION['username'];
}

// --- Session törlés ---
// Egy érték törlése
unset($_SESSION['cart']);

// Minden adat törlése
session_unset();

// Session teljesen megszüntetése
session_destroy();

// --- Session példa: Bejelentkezés ---
// login.php
if ($loginSuccessful) {
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['name'];
    $_SESSION['logged_in'] = true;
    header('Location: dashboard.php');
    exit;
}

// dashboard.php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}
echo "Welcome, " . $_SESSION['username'];



// ============ COOKIE ============

// --- Cookie beállítása ---
// setcookie(name, value, expire, path, domain, secure, httponly)
setcookie('username', 'john_doe', time() + 3600); // 1 óra
setcookie('theme', 'dark', time() + 86400 * 30); // 30 nap
setcookie('remember', '1', time() + 86400 * 365, '/'); // 1 év, minden oldalon

// Biztonságos cookie
setcookie('token', 'abc123', [
    'expires' => time() + 3600,
    'path' => '/',
    'secure' => true, // csak HTTPS
    'httponly' => true, // JavaScript nem éri el
    'samesite' => 'Strict' // CSRF védelem
]);

// --- Cookie olvasása ---
$username = $_COOKIE['username'] ?? 'Guest';
$theme = $_COOKIE['theme'] ?? 'light';

// Létezés ellenőrzés
if (isset($_COOKIE['username'])) {
    echo "Welcome back, " . $_COOKIE['username'];
}

// --- Cookie törlése ---
// Múltbeli lejárati idő beállítása
setcookie('username', '', time() - 3600);

// logout.php
session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
?>
