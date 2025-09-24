<?php
/* 
Program Description: This file allows the user to log out by effectively destroying the session.
Author: Kori Okeshola
Date: 28th April 2025 */

session_start(); // Start or resume session
session_unset(); // Unset session variables
session_destroy(); // Destroy session

// Redirect user to login upon logging out
header("Location: login.php");

exit();
?>