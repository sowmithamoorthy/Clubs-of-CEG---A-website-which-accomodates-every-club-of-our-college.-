<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP PostgreSQL Form</title>
    <script>

        function redirectToComplete() {
            
            window.location.href = 'phome.html';
        }
    </script>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rollno = $_POST['rollno'];
    // Establish a database connection
    $databaseConnection = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres");

    if (!$databaseConnection) {
        die("Connection failed. Error: " . pg_last_error($databaseConnection));
    }

    // Insert data into the database
    $result = pg_query_params($databaseConnection, 'INSERT INTO plogin (email, password,rollno) VALUES ($1, $2,$3)', array($email, $password,$rollno));

    if (!$result) {
        die("Failed to insert data. Error: " . pg_last_error($databaseConnection));
    } else {
        // Redirect to the complete.html page after processing
        header("Location: phome.html");
        exit();
    }

    // Close the database connection
    pg_close($databaseConnection);
}
?>


<form action="newlogin2.php" method="post" onsubmit=" redirectToForm()">
            <input type="email" name="email" placeholder="Email" required>
            <br>
            <input type="password" name="password" placeholder="Password" required>
            <br>
            <input type="text" name="rollno" placeholder="Roll Number" required>
            <br>
            <input type="submit" id="login-button" value="Login">
        </form>>
</body>
</html>
