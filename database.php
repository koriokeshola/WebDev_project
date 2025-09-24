<?php
    /* 
    Program Description: This file establishes the database connection and contains numerous functions relating to admin capabilities..
    Author: Kori Okeshola
    Date: 28th April 2025 */

    session_start();

    $servername = "localhost";
    $email = "root";
    $password = "";
    $dbname = "restaurant_db";

    $conn = new mysqli($servername, $email, $password, $dbname);

    if($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set session variables
    function set_message($message, $type = 'info')
    {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }

    // Check if user is already logged in
    function is_logged_in()
    {
        return isset($_SESSION['user_id']);
    }

    // Redirect user to login page if not logged in
    function verify_login()
    {
        if(!is_logged_in())
        {
            set_message('User not logged in.', 'error');
            header("Location: login.php");
            exit();
        }
    }

    // Check if user is the administrator
    function is_admin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Redirect user to index page if not administrator
    function verify_admin()
    {
        verify_login();
        if(!is_admin())
        {
            set_message('Unauthorised access.', 'error');
            header("Location: index.php");
            exit();
        }
    }

    // Get all user details from SQL database
    function get_user_details($user_id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
?>
