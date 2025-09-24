<?php
/* 
Program Description: This file allows the administrator to read values into the system.
Author: Kori Okeshola
Date: 28th April 2025 */

require_once "database.php"; // Include database connection
require_once "header.php"; // Include common header file
session_start(); // Start or resume web session

// Set administrator based on 'role' variable in session
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Retrieve sanitised input
$search = trim($_GET['search'] ?? '');
// Use SQL wildcard to allow for matching of search term
$search_wildcard = "%{$search}%";

// Show available menu items
$sql = "SELECT * FROM menu WHERE item_name LIKE ? OR category LIKE ?";

// Prevent SQL injection when preparing query
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $search_wildcard, $search_wildcard);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <h2 class="solid-background">🪻 The Orchid Lounge Menu 🪻</h2>

    <!--Search Form -->
    <form action="read.php" method="GET">
        <!-- Sanitise input when searching -->
        <input type="text" name="search" placeholder="Search item/category" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <input type="submit" value="Search">
    </form>
    <br>

    <!-- Menu Table -->
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price</th>
                <!-- Features shown only to admin -->
                <?php if($is_admin): ?>
                    <th>Available</th> <!-- Details of item availability -->
                    <th>Actions</th> <!-- Option to edit and/or delete items -->
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if($result->num_rows > 0): ?>
                <!-- Loop through each item -->
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['item_name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <?php if($is_admin): ?>
                            <td><?= $row['is_available'] ? 'Yes' : 'No' ?></td>
                            <td>
                                <a href="update.php?id=<?= $row['id'] ?>">Update</a> |
                                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Option to go back to homepage -->
    <br><a href="index.php">Back to Main Menu</a>

    <!-- Consistent footer across files -->
    <?php include 'footer.php'; ?>
</body>
</html>
