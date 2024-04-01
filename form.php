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
    $ph_no = $_POST['phone_no'];
    $dept = $_POST['dept'];
    $year = $_POST['year'];
    $rollno = $_POST['rollno'];
    $domain = isset($_POST['domain']) ? implode(', ', $_POST['domain']) : '';
    $reason = $_POST['reason'];
    $club = $_POST['club_name'];

    // Establish a database connection
    $databaseConnection = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres");

    if (!$databaseConnection) {
        die("Connection failed. Error: " . pg_last_error($databaseConnection));
    }

    // Insert data into the database
    $result = pg_query_params(
        $databaseConnection,
        'INSERT INTO form (name, email, phone_no, dept, year, rollno, domain, reason, club_name) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)',
        array($name, $email, $ph_no, $dept, $year, $rollno, $domain, $reason, $club)
    );

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



<form action="form.php" method="post" onsubmit="redirectToComplete()">
<label for="clubname">Club Name :</label>
        <input type="text" id="club_name" name="club_name" required>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="phone_no">Phone Number:</label>
        <input type="tel" id="phone_no" name="phone_no" required>

        <label for="dept">Department:</label>
        <input type="text" id="dept" name="dept" required>

        <label for="year">Year:</label>
        <input type="text" id="year" name="year" required>
        <label for="rollno">Rollno:</label>
    <input type="text" id="rollno" name="rollno"  required>

        <label class="checkbox-label">Interest Domain:</label>
        <label><input type="checkbox" name="domain[]" value="content"> Content</label>
        <label><input type="checkbox" name="domain[]" value="design"> Design</label>
        <label><input type="checkbox" name="domain[]" value="events"> Events</label>
        <label><input type="checkbox" name="domain[]" value="hospitality"> Hospitality</label>
        <label><input type="checkbox" name="domain[]" value="humanrelations"> Human Relations</label><br><br>

        <label class="textarea-label" for="reason">Why did you choose this club?</label>
        <textarea id="reason" name="reason" rows="4" required></textarea><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
