<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEG SPARTENZ - DANCE CLUB OF CEG</title>
    <style>
  
  body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: url('https://theatron225242548.files.wordpress.com/2021/03/theatron-logo-1-3.jpg?w=800') no-repeat center center fixed;
            background-size: cover;
            color: #f1f0f0;
        }
        #instagram-link {
            text-align: center;
            margin: 20px 0;
            color: #fff;
        }

        header {
            text-align: center;
            padding: 20px;
            background-color: #3d1616;
        }

        section {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
        }

        .contact-us {
            width: 100%; /* Adjusted width */
            box-sizing: border-box;
            color: white;
            animation: bounce 2s infinite alternate; /* Added animation */
        }

        .location-description {
            margin-bottom: 20px;
        }

        .join-as-member {
            border: 1px solid #696363;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            position: fixed;
            top: 20px;
            right: 20px;
            box-sizing: border-box;
            cursor: pointer;
            height: 60px;
            background-color: #4a4e4c;
            transition: background-color 0.3s, color 0.3s;
            z-index: 1;
        }

        .join-as-member h2,
        .join-as-member p {
            margin: 0;
            font-size: 14px;
            color: white;
        }

        .join-as-member:hover {
            background-color: #34db74;
            color: #090909;
        }

        .query-box {
            color: white;
            border: 1px solid #514d4d;
            border-radius: 5px;
            padding: 15px;
            width: 50%;
            box-sizing: border-box;
            text-align: left;
            margin-top: 20px;
            margin-right: auto;
            margin-left: auto;
            margin-bottom: 40px;
        }

        .query-box input,
        .query-box textarea {
            color: #222;
            width: calc(100% - 20px);
            margin-bottom: 10px;
            padding: 8px;
            box-sizing: border-box;
        }

        .query-box button {
            background-color: #4a4e4c;
            color: #fff;
            border: none;
            padding: 10px;

            cursor: pointer;
            transition: background-color 0.3s;
        }

        .query-box button:hover {
            background-color: #333;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #4b0f0f;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(30);
            }
            50% {
                transform: translateY(30px);
            }
        }    </style>
</head>
<body>

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $queries = $_POST['queries'];

    // Establish a database connection
    $databaseConnection = pg_connect("host=localhost port=5432 dbname=your_database_name user=your_username password=your_password");

    // Check if the connection is successful
    if (!$databaseConnection) {
        die("Connection failed. Error: " . pg_last_error($databaseConnection));
    }

    // Insert data into the database
    $query = 'INSERT INTO theatron (name, email, queries) VALUES ($1, $2, $3)';
    $result = pg_query_params($databaseConnection, $query, array($name, $email, $queries));

    // Check if the query execution is successful
    if (!$result) {
        die("Failed to insert data. Error: " . pg_last_error($databaseConnection));
    } else {
        // Uncomment the following lines if you want to echo a success message
        // echo "Data inserted successfully!";
        // exit();
    }

    // Close the database connection
    pg_close($databaseConnection);
}
?>
<html>
<header>
<h1>THEATRON - </h1>
</header>
<section>
<div class="contact-us">
    <h2>Contact Us</h2>
    <div class="location-description">
        <p>We are located at:</p>
        <p>Alumni Centre near Vivekananda Auditorium</p>
    </div>
</div>
<div class="join-as-member" onclick="redirectToForm('form.html')">
    <h2>Join</h2>
    <p>Join as Member</p>
</div>
</section>
<div class="query-box">
    <h2>Have a Query?</h2>
    <form action="theatron.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="queries">Any Queries?</label>
        <textarea id="queries" name="queries" rows="4" required></textarea>

        <button type="submit" onclick="redirectToComplete()">Submit</button>
    </form>
</div>
<footer>
    <div id="instagram-link">
        <p>Follow us on Instagram: <a href="https://instagram.com/theatron_au?igshid=OGQ5ZDc2ODk2ZA==" target="_blank">danceclub_ceg</a></p>
    </div>
    <p>&copy; 2023 Theatre club - CEG College. All rights reserved.</p>
</footer>

<script>
    function redirectToForm(destination) {
        window.location.href = destination;
    }
    function redirectToComplete() {
        window.location.href = 'complete.html';
    }
</script>
</body>
</html>
