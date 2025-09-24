<?php
/* 
Program Description: This file allows a user to create an account.
Author: Kori Okeshola
Date: 28th April 2025 */

require_once "database.php"; // Include database connection
require_once "header.php"; // Include common header file
session_start(); // Start or resume web session

$alert = "";

// Take sanitised input
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $first_name = htmlspecialchars(trim($_POST["first_name"]));
    $last_name = htmlspecialchars(trim($_POST["last_name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    
    // Check if passwords match
    if($password !== $confirm_password)
    {
        $alert = "Passwords do not match.";
    }
    else
    {
        $role = "user";
        
        // Check if email already exists in database system
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if($result->num_rows > 0)
        {
            $alert = "Email already exists in our system.";
        }
        else
        {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $role);

            if($stmt->execute())
            {
                // Send message declaring success and redirect to login page
                $_SESSION['message'] = "Successfully registered.";
                $_SESSION['message_type'] = "success";
                header("Location: login.php");
                exit();
            }
            else
            {
                $alert = "Registration failed: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
<div class="section">
    <h2 class="solid-background">Create Account</h2>
    
    <!-- Display alert upon encountering any errors -->
    <?php if(!empty($alert)): ?>
        <div class="alert error"><?php echo $alert; ?></div>
    <?php endif; ?>
    
    <!-- Registration Form - takes sanitised input where necessary -->
    <form action="register.php" method="POST">       
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
        
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required minlength="8">
        <small>Must contain at least 8 characters</small><br><br>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
        
        <input type="submit" value="Register">
    </form>
    
    <!-- Redirect to login page if user has an existing account -->
    <p class="text-center mt-20">
        Already have an account? <a href="login.php">Login here</a>
    </p>
</div>
    
    <!-- Consistent footer across files -->
    <?php include 'footer.php'; ?>
</body>
</html>
