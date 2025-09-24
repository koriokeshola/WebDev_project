<?php
/* Program Description: This file contains a standard header inserted at the top of each file.
Author: Kori Okeshola
Date: 28th April 2025 */

session_start(); // Start or resume web session

// Message display
$message = ''; // Set contents
$message_type = ''; // Set type

// Check if there's a message currently being held as a session variable
if(isset($_SESSION['message']))
{
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'info';
    // Delete message when no longer needed
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🪻 The Orchid Lounge Restaurant 🪻</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <header>
        <!-- List of page options available to user -->
        <h1>The Orchid Lounge</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="read.php">Main Menu</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li><a href="index.php">Admin Dashboard</a></li>
                        <li><a href="create.php">Add Menu Items</a></li>
                        <li><a href="update.php">Edit Menu Items</a></li>
                        <li><a href="delete.php">Delete Menu Items</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Displaying messages to user -->
    <div class="content-container">
        <?php if(!empty($message)): ?>
            <div class="alert <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
</body>
</html>
