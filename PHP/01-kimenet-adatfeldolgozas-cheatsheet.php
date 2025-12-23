<?php
// CLI
// php -S localhost:8080

// ============================================
// PHP KIMENETGENERÁLÁS & ADATFELDOLGOZÁS
// ============================================

// ============ KIMENET GENERÁLÁS ============

// Alapvető kiírás
echo "Hello World";
print "Hello World"; // echo-hoz hasonló
echo "Line 1", "Line 2", "Line 3"; // több paraméter

// Változók kiírása
$name = "János";
echo "Hello $name"; // "Hello János"
echo "Hello {$name}"; // "Hello János" (összetett esetben)
echo 'Hello $name'; // "Hello $name" (szimpla idézőjel: nem helyettesít)

// HTML generálás
echo "<h1>Title</h1>";
echo "<p>Paragraph with $name</p>";
echo '<div class="container">' . $name . '</div>';

// Többsoros string (heredoc)
$html = <<<HTML
<div>
    <h2>$name</h2>
    <p>Content</p>
</div>
HTML;
echo $html;

// Változó értékének kiírása debuggoláshoz
$arr = [1, 2, 3];
print_r($arr); // Array ( [0] => 1 [1] => 2 [2] => 3 )
var_dump($arr); // array(3) { [0]=> int(1) [1]=> int(2) [2]=> int(3) }

// Formázott kiírás
printf("Name: %s, Age: %d", "Anna", 25); // Name: Anna, Age: 25
$formatted = sprintf("%.2f Ft", 1234.5); // "1234.50 Ft"

// ============ TÖMBÖK ============

// Tömb létrehozása
$numbers = [1, 2, 3, 4, 5];
$fruits = array("apple", "banana", "cherry"); // régi szintaxis

// Asszociatív tömb (kulcs-érték párok)
$person = [
    "name" => "Anna",
    "age" => 25,
    "city" => "Budapest"
];

// Tömb hozzáférés
echo $numbers[0]; // 1
echo $person["name"]; // "Anna"

// Tömb módosítás
$numbers[0] = 10;
$person["age"] = 26;

// Elem hozzáadása
$numbers[] = 6; // a végére
array_push($numbers, 7, 8); // több elem hozzáadása
array_unshift($numbers, 0); // az elejére

// Elem eltávolítása
$last = array_pop($numbers); // utolsó elem törlése és visszaadása
$first = array_shift($numbers); // első elem törlése és visszaadása
unset($numbers[2]); // adott index törlése

// ============ TÖMB MŰVELETEK (map, filter, reduce stílusban) ============

// --- ARRAY_MAP: Átalakítás ---
$numbers = [1, 2, 3, 4, 5];

// Duplázás
$doubled = array_map(fn($n) => $n * 2, $numbers); // [2, 4, 6, 8, 10]
$squared = array_map(fn($n) => $n ** 2, $numbers); // [1, 4, 9, 16, 25]

// Objektumok átalakítása
$users = [
    ["name" => "Anna", "age" => 25],
    ["name" => "Béla", "age" => 30]
];
$names = array_map(fn($user) => $user["name"], $users); // ["Anna", "Béla"]

// Több tömb együttes feldolgozása
$first = [1, 2, 3];
$second = [4, 5, 6];
$sums = array_map(fn($a, $b) => $a + $b, $first, $second); // [5, 7, 9]

// --- ARRAY_FILTER: Szűrés ---
$evens = array_filter($numbers, fn($n) => $n % 2 == 0); // [2, 4]
$odds = array_filter($numbers, fn($n) => $n % 2 != 0); // [1, 3, 5]

$adults = array_filter($users, fn($user) => $user["age"] >= 18);

// Üres értékek kiszűrése
$data = [0, 1, false, 2, "", 3, null];
$filtered = array_filter($data); // [1, 2, 3]

// --- ARRAY_REDUCE: Összegzés, akkumuláció ---
$sum = array_reduce($numbers, fn($carry, $n) => $carry + $n, 0); // 15
$product = array_reduce($numbers, fn($carry, $n) => $carry * $n, 1); // 120
$max = array_reduce($numbers, fn($carry, $n) => max($carry, $n), PHP_INT_MIN);

// Objektummá alakítás
$userById = array_reduce($users, function($carry, $user) {
    $carry[$user["name"]] = $user;
    return $carry;
}, []);

// Összefűzés stringgé
$concat = array_reduce($numbers, fn($carry, $n) => $carry . $n, ""); // "12345"

// --- ARRAY_WALK: Iteráció minden elemen ---
array_walk($numbers, callback: function($value, $key) {
    echo "$key: $value\n";
});

// Tömb módosítása referenciával
array_walk($numbers, function(&$value) {
    $value *= 2;
});

// ============ TOVÁBBI HASZNOS TÖMB FÜGGVÉNYEK ============

// Keresés
$position = array_search(3, $numbers); // 2 (index)
$exists = in_array(3, $numbers); // true
$key = array_key_exists("name", $person); // true

// Rendezés
sort($numbers); // növekvő sorrend
rsort($numbers); // csökkenő sorrend
asort($person); // érték szerint rendez, megtartja a kulcsokat
ksort($person); // kulcs szerint rendez
usort($users, fn($a, $b) => $a["age"] <=> $b["age"]); // custom rendezés

// Darabszám
$count = count($numbers); // 5
$length = sizeof($numbers); // ugyanaz, mint count

// Értékek és kulcsok
$keys = array_keys($person); // ["name", "age", "city"]
$values = array_values($person); // ["Anna", 25, "Budapest"]

// Összefésülés
$merged = array_merge([1, 2], [3, 4]); // [1, 2, 3, 4]
$combined = $arr1 + $arr2; // + operátor

// Szeletelés
$slice = array_slice($numbers, 0, 3); // első 3 elem
$chunk = array_chunk($numbers, 2); // [[1, 2], [3, 4], [5]]

// Egyedi értékek
$unique = array_unique([1, 2, 2, 3, 3, 4]); // [1, 2, 3, 4]

// Tömb megfordítás
$reversed = array_reverse($numbers);

// Tömb kitöltés
$filled = array_fill(0, 5, "x"); // ["x", "x", "x", "x", "x"]

// Tömb lapítás (flatten)
$nested = [[1, 2], [3, 4], [5]];
$flattened = array_merge(...$nested); // [1, 2, 3, 4, 5]

// ============ STRING MŰVELETEK ============

$text = "Hello World";

// Alapvető műveletek
$upper = strtoupper($text); // "HELLO WORLD"
$lower = strtolower($text); // "hello world"
$length = strlen($text); // 11
$trimmed = trim("  hello  "); // "hello"

// Keresés
$pos = strpos($text, "World"); // 6
$contains = str_contains($text, "World"); // true (PHP 8+)
$starts = str_starts_with($text, "Hello"); // true (PHP 8+)
$ends = str_ends_with($text, "World"); // true (PHP 8+)

// Csere
$replaced = str_replace("World", "PHP", $text); // "Hello PHP"
$replaced2 = str_ireplace("world", "PHP", $text); // case-insensitive

// Szétválasztás és összeillesztés
$parts = explode(" ", $text); // ["Hello", "World"]
$joined = implode(" ", $parts); // "Hello World"
$joined2 = join(", ", $parts); // "Hello, World"

// Substring
$sub = substr($text, 0, 5); // "Hello"
$sub2 = substr($text, -5); // "World"

// ============ KOMBINÁLT PÉLDÁK ============

// Szűrés + Rendezés + Map
$products = [
    ["name" => "Laptop", "price" => 300000, "category" => "electronics"],
    ["name" => "Phone", "price" => 150000, "category" => "electronics"],
    ["name" => "Book", "price" => 3000, "category" => "books"]
];

// Drága elektronikai cikkek ÁFÁ-val
$expensiveElectronics = array_filter($products,
    fn($p) => $p["category"] == "electronics" && $p["price"] > 100000
);
usort($expensiveElectronics, fn($a, $b) => $b["price"] <=> $a["price"]);
$withVAT = array_map(function($p) {
    return array_merge($p, ["priceWithVAT" => $p["price"] * 1.27]);
}, $expensiveElectronics);

// Csoportosítás kategória szerint
$byCategory = array_reduce($products, function($carry, $p) {
    $category = $p["category"];
    if (!isset($carry[$category])) {
        $carry[$category] = [];
    }
    $carry[$category][] = $p;
    return $carry;
}, []);

// Összesítés kategóriánként
$totalByCategory = array_reduce($products, function($carry, $p) {
    $category = $p["category"];
    $carry[$category] = ($carry[$category] ?? 0) + $p["price"];
    return $carry;
}, []);

// Átlag számítás
$totalPrice = array_reduce($products, fn($sum, $p) => $sum + $p["price"], 0);
$avgPrice = $totalPrice / count($products);

// Legdrágább termék
$mostExpensive = array_reduce($products, fn($max, $p) =>
    ($max === null || $p["price"] > $max["price"]) ? $p : $max
);

// ============ HTML TÁBLÁZAT GENERÁLÁS ============

echo "<table border='1'>";
echo "<tr><th>Name</th><th>Price</th><th>Category</th></tr>";
foreach ($products as $product) {
    echo "<tr>";
    echo "<td>{$product['name']}</td>";
    echo "<td>{$product['price']} Ft</td>";
    echo "<td>{$product['category']}</td>";
    echo "</tr>";
}
echo "</table>";

// Vagy tömörebb verzió:
echo "<table border='1'>";
echo "<tr><th>Name</th><th>Price</th><th>Category</th></tr>";
echo implode("", array_map(fn($p) =>
    "<tr><td>{$p['name']}</td><td>{$p['price']} Ft</td><td>{$p['category']}</td></tr>",
    $products
));
echo "</table>";

// ============ LISTA GENERÁLÁS ============

echo "<ul>";
foreach ($products as $product) {
    echo "<li>{$product['name']} - {$product['price']} Ft</li>";
}
echo "</ul>";

// Lambda verzió:
echo "<ul>" . implode("", array_map(
    fn($p) => "<li>{$p['name']} - {$p['price']} Ft</li>",
    $products
)) . "</ul>";

// ============ SELECT (DROPDOWN) GENERÁLÁS ============

$categories = ["electronics", "books", "clothing"];

echo "<select name='category'>";
foreach ($categories as $cat) {
    echo "<option value='$cat'>$cat</option>";
}
echo "</select>";

// Lambda verzió:
echo "<select name='category'>" .
    implode("", array_map(fn($c) => "<option value='$c'>$c</option>", $categories)) .
    "</select>";

// ============ SZÁMÍTÁSOK ============

// Matematikai műveletek
$min = min($numbers); // 1
$max = max($numbers); // 5
$sum = array_sum($numbers); // 15
$product2 = array_product($numbers); // 120

// Átlag
$average = array_sum($numbers) / count($numbers);

// Statisztika
$ages = array_map(fn($u) => $u["age"], $users);
$avgAge = array_sum($ages) / count($ages);
$minAge = min($ages);
$maxAge = max($ages);

// ============ GYAKORLATI PÉLDÁK ============

// Hallgatók átlag jegyei
$students = [
    ["name" => "Anna", "grades" => [4, 5, 3, 5]],
    ["name" => "Béla", "grades" => [3, 3, 4, 2]]
];

$averages = array_map(function($s) {
    $avg = array_sum($s["grades"]) / count($s["grades"]);
    return ["name" => $s["name"], "average" => $avg];
}, $students);

// CSV generálás
$csv = implode("\n", array_map(fn($s) =>
    $s["name"] . "," . implode(",", $s["grades"]),
    $students
));

// JSON kimenet
header('Content-Type: application/json');
echo json_encode($students);

// Hallgatók szűrése átlag alapján
$passed = array_filter($averages, fn($s) => $s["average"] >= 2.0);

// Top N elem
usort($averages, fn($a, $b) => $b["average"] <=> $a["average"]);
$top3 = array_slice($averages, 0, 3);

// Pagination
$page = 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;
$paginated = array_slice($products, $offset, $perPage);

// Duplikátumok eltávolítása
$items = [1, 2, 2, 3, 3, 3, 4];
$unique2 = array_values(array_unique($items));

// Darabszám megszámlálása
$fruits2 = ["apple", "banana", "apple", "cherry", "banana", "apple"];
$counts = array_count_values($fruits2); // ["apple" => 3, "banana" => 2, "cherry" => 1]

?>
