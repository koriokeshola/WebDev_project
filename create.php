<?php
/* 
Program Description: This program allows the administrator to add values to the system.
Author: Kori Okeshola
Date: 28th April 2025 */

require_once "database.php"; // Include database connection
require_once "header.php"; // Include common header file
session_start(); // Start or resume web session

// Authenticate user to ensure admin access only
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
{
    die("Access denied.");
}

$alert = "";

// Take sanitised input
if($_SERVER["REQUEST_METHOD"] == "POST")
{

    $item_name = htmlspecialchars(trim($_POST['item_name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $category = $_POST['category'];
    $price = floatval($_POST['price']); // Ensure price is of float value

    // Set items to available by default
    $is_available = isset($_POST['is_unavailable']) ? 0 : 1;

    // Ensure that required fields are filled and price is a valid amount
    if(!empty($item_name) && !empty($category) && $price > 0)
    {
        // Prevent SQL injection when preparing query
        $stmt = $conn->prepare("INSERT INTO menu (item_name, description, category, price, is_available) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdi", $item_name, $description, $category, $price, $is_available);

        // Execute query and display success/error message
        if ($stmt->execute())
        {
            $alert = "<span style='color: green;'>✔ Item added successfully.</span>";
            header("Location: index.php"); // Redirect after successful insertion
            exit();
        }
        else
        {
            $alert = "<span style='color: red;'>Error adding item: " . htmlspecialchars($stmt->error) . "</span>";
        }
    }
    else
    {
        $alert = "<span style='color: red;'>Please fill in all required fields and enter valid price amount.</span>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <title>Add Menu Items</title>
        <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <h2 class="solid-background">Add Item to Menu</h2>

    <!-- Display success or error message -->
    <?php if($alert) echo "<p style='color: green;'>$alert</p>"; ?>

    <!-- Form for creating menu items -->
    <form action="create.php" method="POST">
        <label for="item_name">Item:</label><br>
        <input type="text" name="item_name" required><br><br>

        <label for="description">Description:</label><br>
        <textarea name="description" rows="3" cols="20"></textarea><br><br>

        <label for="category">Category:</label><br>
        <select name="category" required>
            <option value="starter">Starter</option>
            <option value="main">Main</option>
            <option value="dessert">Dessert</option>
            <option value="drink">Drink</option>
        </select><br><br>

        <label for="price">Price:</label><br>
        <input type="number" name="price" step="0.01" required><br><br>

        <label><input type="checkbox" name="is_unavailable">Item Unavailable</label><br><br>

        <input type="submit" value="Add Item">
    </form>

    <!-- Option to go back to homepage -->
    <br><a href="index.php">Back to Main Menu</a>

    <!-- Consistent footer across files -->
    <?php include 'footer.php'; ?>
</body>
</html>