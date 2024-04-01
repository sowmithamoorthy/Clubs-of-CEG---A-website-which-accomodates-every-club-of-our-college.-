<?php
session_start();

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the login credentials
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Assume validation is successful
    // Store the student's email in the session
    $_SESSION['login1'] = $email;

    // Redirect to the display_answers.php page
    header("Location: display_answers.php");
    exit();
}

// Check if the student email is set in the session
if (!isset($_SESSION['login1'])) {
    die("Student email not found. Please log in.");
}

// Get the last valid email from the 'login1' table
$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");

// Check the connection
if (!$dbconn) {
    die("Connection failed: " . pg_last_error());
}

$lastLoginQuery = "SELECT email FROM login1 WHERE email = $1 ORDER BY timestamp_column DESC LIMIT 1";
$lastLoginResult = pg_query_params($dbconn, $lastLoginQuery, array($_SESSION['login1']));

if (!$lastLoginResult) {
    die("Query failed: " . pg_last_error($dbconn));
}

// Get the last valid email
$row = pg_fetch_assoc($lastLoginResult);
$lastValidEmail = $row['email'];

// Debugging: Output the last valid email
echo "Last Valid Email: " . htmlspecialchars($lastValidEmail) . "<br>";

// Fetch responses for the specific student from the 'answers' table
$query = "SELECT question, answer FROM answers WHERE email = $1";
$result = pg_query_params($dbconn, $query, array($lastValidEmail));

// Check if the query was successful
if (!$result) {
    die("Query failed: " . pg_last_error($dbconn));
}

// Display the questions and answers in a table
echo "<table border='1'>
    <tr>
        <th>Question</th>
        <th>Answer</th>
    </tr>";

// Check if there are rows in the result
if (pg_num_rows($result) > 0) {
    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['question']) . "</td>
                <td>" . htmlspecialchars($row['answer']) . "</td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='2'>No records found</td></tr>";
}

echo "</table>";

// Close the database connection
pg_close($dbconn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answers Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .no-records {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>

</body>
</html>
