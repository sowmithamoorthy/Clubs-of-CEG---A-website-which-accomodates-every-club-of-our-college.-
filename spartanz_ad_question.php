


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - spartanz Club</title>
    <style>
        /* Your existing styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        form {
            margin-top: 10px;
        }

        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .no-records {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>

<h1>Admin Page - SPARTANZ Club</h1>




<?php
// Connect to PostgreSQL database
$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=postgres");

// Check the connection
if (!$dbconn) {
    die("Connection failed: " . pg_last_error());
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'], $_POST['question_id'], $_POST['submit_answer'])) {
    // Get the answer, question_id, and email from the form submission
    $answer = $_POST['answer'];
    $questionId = $_POST['question_id'];
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Fetch the question from the 'spartanz_con' table
    $fetchQuestionQuery = "SELECT name, email, question FROM spartanz_con WHERE id = $questionId";
    $fetchQuestionResult = pg_query($dbconn, $fetchQuestionQuery);

    if ($fetchQuestionResult) {
        $questionRow = pg_fetch_assoc($fetchQuestionResult);
        $name = $questionRow['name'];
        $question = $questionRow['question'];

        // Insert the question, answer, and email into the 'spartanz_answer' table
        $insertQuery = "INSERT INTO spartanz_answer (email, question, answer) VALUES ('$email', '$question', '$answer')";
        $insertResult = pg_query($dbconn, $insertQuery);

        if (!$insertResult) {
            echo "Failed to insert answer. Error: " . pg_last_error($dbconn);
        } else {
            // Delete the question from the 'spartanz_con' table
            $deleteQuery = "DELETE FROM spartanz_con WHERE id = $questionId";
            $deleteResult = pg_query($dbconn, $deleteQuery);

            // Check for errors
            if ($deleteResult === false) {
                echo "Failed to delete the question. Error: " . pg_last_error($dbconn);
            } else {
                // Display alert after successful submission
                echo "<script>alert('Answer sent successfully!');</script>";
                // Redirect after a delay of 2 seconds (adjust the delay as needed)
                echo "<script>setTimeout(function(){ window.location.href = '{$_SERVER['PHP_SELF']}'; }, 2000);</script>";
                exit();
            }
        }
    } else {
        echo "Failed to fetch question. Error: " . pg_last_error($dbconn);
    }
}

// Fetch queries from the spartanz_con table
$query = "SELECT * FROM spartanz_con";

// Execute the query
$result = pg_query($dbconn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . pg_last_error($dbconn));
}

// Display the queries in a table with an answer form
echo "<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Question</th>
        <th>Answer</th>
    </tr>";

// Check if there are rows in the result
if (pg_num_rows($result) > 0) {
    while ($row = pg_fetch_assoc($result)) {
        // Check if 'id', 'name', 'email', and 'question' keys exist in $row array
        $questionId = isset($row['id']) ? $row['id'] : null;
        $name = isset($row['name']) ? $row['name'] : '';
        $email = isset($row['email']) ? $row['email'] : '';
        $question = isset($row['question']) ? $row['question'] : '';

        echo "<tr>
                <td>{$name}</td>
                <td>{$email}</td>
                <td>{$question}</td>
                <td>
                    <form method='POST' action=''>
                        <input type='hidden' name='question_id' value='$questionId'>
                        <input type='hidden' name='email' value='{$email}'>
                        <textarea name='answer' rows='3' cols='30'></textarea>
                        <button type='submit' name='submit_answer'>Submit Answer</button>
                    </form>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No records found</td></tr>";
}

echo "</table>";

// Close the database connection
pg_close($dbconn);
?>

</html>