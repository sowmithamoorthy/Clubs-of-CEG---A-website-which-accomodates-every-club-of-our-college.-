<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP PostgreSQL OOP Example</title>
    <script>
        function validateAndLogin() {
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            // Validate if '@' is included in the email
            if (!email.includes('@')) {
                document.getElementById('emailHint').innerText = 'Email must contain @ symbol';
                return; // Do not proceed with login
            }

            // Clear the hint if validation is successful
            document.getElementById('emailHint').innerText = '';

            // Perform authentication, e.g., send data to server for validation
            // For demonstration purposes, let's assume login is successful

            // Call the function to insert data into the database using AJAX
            insertDataIntoDatabase(email, password);
        }

        function insertDataIntoDatabase(email, password) {
            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure it: specify the type of request and the URL
            xhr.open('POST', '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', true);

            // Set the request header to indicate that we are sending form data
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Define the callback function to handle the response
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    // The request was successful
                    console.log(xhr.responseText);

                    // Redirect to the next page
                    window.location.href = 'home1.html';
                } else {
                    // The request failed
                    console.error('Error:', xhr.status, xhr.statusText);
                }
            };

            // Construct the data to be sent in the request body
            var data = 'email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password);

            // Send the request with the data
            xhr.send(data);
        }
    </script>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['email'];
    $password = $_POST['password'];

    // Establish a database connection
    $databaseConnection = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres");

    if ($databaseConnection) {
        // Prepare and execute the INSERT query
        $result = pg_query_params($databaseConnection, 'INSERT INTO login (email, password) VALUES ($1, $2)', array($username, $password));

        if ($result) {
            // Data inserted successfully, redirect to home1.html
            header('Location: home1.html');
            exit(); // Ensure that no further output is sent
        } else {
            echo "Failed to insert data.<br>";
        }

        // Close the database connection
        pg_close($databaseConnection);
    } else {
        echo "Connection failed.<br>";
    }

    // Terminate the script after processing the POST request
    exit();
}
?>


<!-- Your HTML form -->
<form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <h2>Login</h2>
    <div class="input-container">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <p id="emailHint" class="hint"></p> <!-- Hint element -->
    </div>

    <div class="input-container">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <!-- Change the button type to "submit" -->
    <button type="submit" onclick="validateAndLogin()">Login</button>
</form>

</body>
</html>
