<?php

$shop = [
  [ "brand" => "Homemade", "type"  => "Dark chocolate", "price" => 2000 ],
  [ "brand" => "Grandma's", "type"  => "Milk chocolate", "price" => 1500 ],
  [ "brand" => "Worldsweet", "type"  => "Milk chocolate", "price" => 3000 ],
  [ "brand" => "Worldsweet", "type"  => "Dark chocolate", "price" => 4000 ],
  [ "brand" => "Worldsweet", "type"  => "Orange essence", "price" => 4000 ],
  [ "brand" => "Homemade", "type"  => "Milk chocolate", "price" => 1000 ],
  [ "brand" => "Speziale", "type"  => "Apple & Cinnamon", "price" => 1000 ]
];

$types = array_unique(array_column($shop, 'type'));
sort($types);

$brands = array_unique(array_column($shop, 'brand'));
sort($brands);

function findPrice($shop, $brand, $type) {
    foreach ($shop as $item) {
        if ($item['brand'] === $brand && $item['type'] === $type) {
            return $item['price'];
        }
    }
    return null;
}

// Calculate min and max prices
$prices = array_column($shop, 'price');
$minPrice = min($prices);
$maxPrice = max($prices);

// Calculate average price for each brand
function calculateBrandAverage($shop, $brand) {
    $brandPrices = [];
    foreach ($shop as $item) {
        if ($item['brand'] === $brand) {
            $brandPrices[] = $item['price'];
        }
    }
    return count($brandPrices) > 0 ? array_sum($brandPrices) / count($brandPrices) : 0;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="index.css">
  <title>Task 1</title>
</head>
<body>
  <h1>Task 1: Candies</h1>
  <table>
    <tr>
      <th></th>
    <?php
      foreach ($types as $type) {
        echo "<th>$type</th>";
      }
    ?>
      <th>Average</th>
    </tr>
    <?php
      foreach ($brands as $brand) {
        echo "<tr>";
        echo "<th>$brand</th>";

        foreach ($types as $type) {
          $price = findPrice($shop, $brand, $type);
          if ($price !== null) {
            $class = '';
            if ($price === $minPrice) {
              $class = ' class="lowest"';
            } elseif ($price === $maxPrice) {
              $class = ' class="largest"';
            }
            echo "<td$class>$price</td>";
          } else {
            echo "<td></td>";
          }
        }

        $average = calculateBrandAverage($shop, $brand);
        echo "<td>" . round($average, 2) . "</td>";

        echo "</tr>";
      }
    ?>
  </table>
</body>
</html>