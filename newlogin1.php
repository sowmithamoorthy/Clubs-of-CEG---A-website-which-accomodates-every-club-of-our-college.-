<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP PostgreSQL Form</title>
    <script>
        function redirectToPage(message, page) {
            if (message !== undefined) {
                alert(message);
            }
            window.location.href = page;
        }
    </script>
</head>
<body>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rollno = $_POST['rollno'];

    // Establish a database connection
    $databaseConnection = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres");

    if (!$databaseConnection) {
        die("Connection failed. Error: " . pg_last_error($databaseConnection));
    }

    // Check if the form is for login or signup
    if (isset($_POST['login'])) {
        // Login form submitted
        
        $result = pg_query_params($databaseConnection, 'SELECT * FROM login WHERE email = $1 AND password = $2 AND rollno = $3', array($email, $password, $rollno));

        if (!$result) {
            die("Query failed. Error: " . pg_last_error($databaseConnection));
        }

        $numRows = pg_num_rows($result);

        if ($numRows > 0) {
            // User exists, allow login

            // Insert data into login1 table
            $insertLogin1Result = pg_query_params($databaseConnection, 'INSERT INTO login1 (email, password, rollno) VALUES ($1, $2, $3)', array($email, $password, $rollno));

            if (!$insertLogin1Result) {
                die("Query failed. Error: " . pg_last_error($databaseConnection));
            }

            $_SESSION['login1'] = $email;  // Store the email in the session
            echo '<script>redirectToPage("Login successful!", "home1.html");</script>';
            exit();
        } else {
            // User doesn't exist, display an error message in an alert and redirect to newlogin1.html
            echo '<script>alert("Invalid credentials. Please sign up first.");';
            echo 'window.location.href = "newlogin1.html";</script>';
            exit();
        }
    } elseif (isset($_POST['signup'])) {
        // Signup form submitted
        $checkEmailResult = pg_query_params($databaseConnection, 'SELECT * FROM login WHERE email = $1', array($email));

        if (!$checkEmailResult) {
            die("Query failed. Error: " . pg_last_error($databaseConnection));
        }

        $numRows = pg_num_rows($checkEmailResult);

        if ($numRows > 0) {
            // Email already exists, display an error message in an alert and redirect to newlogin1.html
            echo '<script>alert("Email already registered. Please use a different email.");';
            echo 'window.location.href = "newlogin1.html";</script>';
            exit();
        } else {
            // Email is not registered, insert the new user data
            $insertResult = pg_query_params($databaseConnection, 'INSERT INTO login (email, password, rollno) VALUES ($1, $2, $3)', array($email, $password, $rollno));
        
            if (!$insertResult) {
                die("Query failed. Error: " . pg_last_error($databaseConnection));
            }
        
            // Registration successful message
            echo '<script>alert("Registration successful! You can now log in.");';
            echo 'window.location.href = "newlogin1.html";</script>';
        }
    }

    // Close the database connection
    pg_close($databaseConnection);
}
?>

<!-- Combined Login and Signup Form -->
<form action="newlogin1.php" method="post">
    <h2>Login</h2>
    <input type="email" name="email" placeholder="Email" required>
    <br>
    <input type="password" name="password" placeholder="Password" required>
    <br>
    <input type="text" name="rollno" placeholder="Roll Number" required>
    <br>
    <input type="submit" name="login" value="Login">
    <input type="submit" name="signup" value="Sign Up">
</form>

</body>
</html>
