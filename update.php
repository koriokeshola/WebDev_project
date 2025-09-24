<?php
/* 
Program Description: This file allows the administrator to edit values within the system.
Author: Kori Okeshola
Date: 28th April 2025 */

require_once "database.php"; // Include database connection
require_once "header.php"; // Include common header file
session_start(); // Start or resume web session

// Authenticate user to ensure admin access only
if(empty($_SESSION['role']) || $_SESSION['role'] !== 'admin')
{
    die("Unauthorised access.");
}

// Session variable for error/status messages
$alert = "";

// Check if ID has been passed, otherwise display items
if(!isset($_GET['id']))
{
    // Fetch items and display, with option to edit
    $stmt = $conn->prepare("SELECT id, item_name, category FROM menu ORDER BY category, item_name");
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<h2>Select item to edit:</h2>";
    echo "<ul>";
    while($row = $result->fetch_assoc())
    {
        echo "<li><a href='update.php?id=" . $row['id'] . "'>" . 
        htmlspecialchars($row['item_name']) . " (" . htmlspecialchars($row['category']) . ")</a></li>";
    }
    echo "</ul>";
    exit(); // Exit seamlessly to prevent code overlap
}

// Form handling, with sanitised values
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $item_name = htmlspecialchars(trim($_POST['item_name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $category = $_POST['category'];
    $price = floatval($_POST['price']); // Ensure price is of float value
    $is_available = isset($_POST['is_unavailable']) ? 0 : 1; // Use checkbox value for availability

    // Prepare SQL query to avoid injection
    $stmt = $conn->prepare("UPDATE menu SET item_name=?, description=?, category=?, price=?, is_available=? WHERE id=?");
    $stmt->bind_param("sssdii", $item_name, $description, $category, $price, $is_available, $item_id);

    // Send alert message based on whether the update was successful
    if($stmt->execute())
    {
        $alert = "Item updated successfully.";

        // Refresh data to avoid errors
        $menu_item['item_name'] = $item_name;
        $menu_item['description'] = $description;
        $menu_item['category'] = $category;
        $menu_item['price'] = $price;
        $menu_item['is_available'] = $is_available;
    }
    else
    {
        $alert = "Error updating item: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu Item</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <h2 class="solid-background">Edit Menu Item</h2>
    
    <!-- Display alert message if necessary -->
    <?php if($alert) echo "<p style='color: green;'>$alert</p>"; ?>

    <!-- Form for editing menu -->
    <form action="" method="POST">
        <label>Item Name:</label><br>
        <input type="text" name="item_name" value="<?= htmlspecialchars($menu_item['item_name']) ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="3" cols="30"><?= htmlspecialchars($menu_item['description']) ?></textarea><br><br>

        <label>Category:</label><br>
        <select name="category" required>
            <option value="starter" <?= $menu_item['category'] == 'starter' ? 'selected' : '' ?>>Starter</option>
            <option value="main" <?= $menu_item['category'] == 'main' ? 'selected' : '' ?>>Main</option>
            <option value="dessert" <?= $menu_item['category'] == 'dessert' ? 'selected' : '' ?>>Dessert</option>
            <option value="drink" <?= $menu_item['category'] == 'drink' ? 'selected' : '' ?>>Drink</option>
        </select><br><br>

        <label>Price (in €):</label><br>
        <input type="number" name="price" step="0.01" value="<?= $menu_item['price'] ?>" required><br><br>

        <!-- Option to mark unavailable ie. if an item is out of stock -->
        <label><input type="checkbox" name="is_unavailable" <?= $menu_item['is_available'] ? '' : 'checked' ?>> Mark as Unavailable</label><br><br>

        <input type="submit" value="Update Item">
    </form>

    <!-- Option to go back to homepage -->
    <br><a href="read.php">Back to Main Menu</a>

    <!-- Consistent footer across files -->
    <?php include 'footer.php'; ?>
</body>
</html>
