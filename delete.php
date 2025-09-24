<?php
/* 
Program Description: This file allows the administrator to delete any values from the system.
Author: Kori Okeshola
Date: 28th April 2025 */

require_once "database.php"; // Include database connection
require_once "header.php"; // Include common header file
session_start(); // Start or resume web session

// Authenticate user to ensure admin access only
if(empty($_SESSION['role']) || $_SESSION['role'] !== 'admin')
{
    header("Location: index.php");
    exit();
}

// Check if item ID is set
if(isset($_GET['id']))
{
    $menu_id = (int)$_GET['id'];

    // Use SQL query to delete from database
    $stmt = $conn->prepare("DELETE FROM menu WHERE id = ?");
    $stmt->bind_param("i", $menu_id);

    if($stmt->execute())
    {
        $_SESSION['message'] = "Item deleted successfully.";
    }
    else
    {
        $_SESSION['message'] = "Error deleting item: " . $stmt->error;
    }
}

// Redirect to read page
header("Location: read.php");
exit();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Menu Item</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <h2 class="solid-background">Delete Menu Item</h2>
    
    <!-- Print alert message -->
    <?php if($alert): ?>
        <p style="color:green;"><?= $alert ?></p>
        <a href="read.php">Back to Main Menu</a>
    <?php elseif($menu_item): ?>
        <p>Are you sure you want to delete "<strong><?= htmlspecialchars($menu_item['item_name']) ?></strong>"?</p>
        <form action="" method="POST">
            <input type="submit" value="Yes" style="background:red;color:white;">
            <a href="read.php" style="margin-left: 10px;">Cancel</a>
        </form>
    <?php endif; ?>
    <?php include 'footer.php'; ?>
</body>
</html>
