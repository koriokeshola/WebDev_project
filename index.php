<?php
/* 
Program Description: This program is the main menu for the site.
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

// Session variables
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];

// Retrieve the user's email from the database
$stmt = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$email = $user['email'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🪻 The Orchid Lounge Restaurant 🪻</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <!-- Consistent header across files -->
    <header>
        <h1 class="solid-background">Welcome!</h1>

        <!-- Sanitised welcome greeting-->
        <p class="welcome">Welcome, <?= htmlspecialchars($email) ?> to The Orchid Lounge Website!</p>
        <nav>
            <ul>
                <!-- Navigation menu -->
                <li><a href="create.php">Create Products</a></li>
                <li><a href="read.php">Read Products</a></li>
                <li><a href="update.php">Update Products</a></li>
                <li><a href="delete.php">Delete Products</a></li>
                <li><a href="login.php">Log into System</a></li>
                <li><a href="logout.php">Log out of System</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="content">
            <h2>Dashboard</h2>
            <p>Welcome to your restaurant management dashboard.</p>
        </section>
    </main>
    <!-- Consistent footer across files -->
    <?php include 'footer.php'; ?>
</body>
</html>
