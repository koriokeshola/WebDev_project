<?php
/* 
Program Description: In this file, the user logs into the database system.
Author: Kori Okeshola
Date: 28th April 2025 */

require_once "database.php"; // Include database connection
require_once "header.php"; // Include common header file
session_start(); // Start or resume web session

// Redirect user to homepage if already logged in
if(is_logged_in())
{
    header("Location: index.php");
    exit();
}

// Variable for storing messages/potential errors
$alert = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Take sanitised input
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);
    
    // Search for user using email
    $sql = "SELECT * FROM users WHERE email = ?";

    // Prevent SQL injection when preparing query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Get result of query
    $result = $stmt->get_result();
    
    // Check if a unique user is found
    if($result->num_rows === 1)
    {
        // Fetch user details
        $user = $result->fetch_assoc();

        // Check password
        if($password === $user['password'])
        {
            // Regenerate ID for security purposes
            session_regenerate_id(true);

            // Set user variables as session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect to homepage upon login
            header("Location: index.php");

            exit();
        }
        else
        {
            $alert = "Invalid credentials.";
        }
    }
    else
    {
        $alert = "User not found.";
    }
}

// Function for sanitisting data
function test_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
<div class="section">
    <h2 class="solid-background">Login to Database Management System</h2>

    <!-- Login form -->
    <form action="login.php" method="POST">
        <label>Email:</label><br>
        <input type="text" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

    <!-- Option to register instead -->
    <p class="text-center mt-20">
        Don't have an account? <a href="register.php">Register here</a>
    </p>
</div>

<!-- Show alert if required -->
<?php if (!empty($alert)): ?>
    <p style="color:red;"><?php echo $alert; ?></p>
<?php endif; ?>

<!-- Consistent footer across files -->
<?php include 'footer.php'; ?>
</body>
</html>
