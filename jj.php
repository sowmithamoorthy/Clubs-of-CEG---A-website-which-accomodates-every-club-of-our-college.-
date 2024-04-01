<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the search term from the form
    $searchTerm = $_GET['search'];

    // Connecting, selecting database
    $databaseConnection = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=postgres");
    if (!$databaseConnection) {
        die("Connection failed. Error: " . pg_last_error($databaseConnection));
    }

    // Performing SQL query with a WHERE clause
    $searchTerm = pg_escape_string($databaseConnection, $searchTerm);

    // Performing SQL query
    $query = "SELECT * FROM jonor WHERE jonor_name LIKE '%$searchTerm%'";
    $result = pg_query($databaseConnection, $query) or die('Query failed: ' . pg_last_error());

    // Start building the HTML for the results
    $output = "<table>\n";
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $output .= "\t<tr>\n";
        foreach ($line as $col_value) {
            $output .= "\t\t<td>$col_value</td>\n";
        }
        $output .= "\t</tr>\n";
    }
    $output .= "</table>\n";

    // Closing the database connection
    pg_close($databaseConnection);

    // Return the HTML for the search results
    echo $output;
}
?>
