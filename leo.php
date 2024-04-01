<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP PostgreSQL Form</title>
    <script>

        function redirectToComplete() {
            
            window.location.href = 'complete.html';
        }
    </script>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $queries = $_POST['queries'];

    // Establish a database connection
    $databaseConnection = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres");

    if (!$databaseConnection) {
        die("Connection failed. Error: " . pg_last_error($databaseConnection));
    }

    // Insert data into the database
    $result = pg_query_params($databaseConnection, 'INSERT INTO leo (name, email, queries) VALUES ($1, $2, $3)', array($name, $email, $queries));
    $result = pg_query_params($databaseConnection, 'INSERT INTO leo_con (name, email, question) VALUES ($1, $2, $3)', array($name, $email, $queries));

    if (!$result) {
        die("Failed to insert data. Error: " . pg_last_error($databaseConnection));
    } else {
        // Redirect to the complete.html page after processing
        header("Location: complete.html");
        exit();
    }

    // Close the database connection
    pg_close($databaseConnection);
}
?>


<form action="leo.php" method="post" onsubmit="redirectToComplete()">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="queries">Any Queries?</label>
            <textarea id="queries" name="queries" rows="4" required></textarea>

            <button type="submit">Submit</button>
        </form>
</body>
</html>
