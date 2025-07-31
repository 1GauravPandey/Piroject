<?php
// Include database connection file
include 'db.php';

// SQL query to select the latest 4 products ordered by descending id
$sql = "SELECT * FROM products ORDER BY id DESC LIMIT 4";

// Execute the query and get the result set
$result = mysqli_query($conn, $sql);

// Check if the query execution was successful
if (!$result) {
    // If query failed, stop execution and display error message
    die("Query failed: " . mysqli_error($conn));
}

// Loop through each row of the result set
while ($row = mysqli_fetch_assoc($result)) {
    // Sanitize and format the data from database for safe output
    $id = (int)$row['id']; // Cast id to integer for security
    $name = htmlspecialchars($row['name'], ENT_QUOTES); // Prevent XSS by escaping special chars
    $price = number_format($row['price'], 2); // Format price with 2 decimal places
    $image = htmlspecialchars($row['image'], ENT_QUOTES); // Escape image filename
    
    // Output product information as HTML block for carousel item
    echo "<div class='carousel-item'>
            <img src='images/{$image}' alt='{$name}' />
            <h4>{$name}</h4>
            <p class='price'>रु{$price}</p>
            <button onclick='addToCart({$id})'>Add to Cart</button>
          </div>";
}
?>

