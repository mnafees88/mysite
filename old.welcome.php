<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Check if filter values are submitted
$filterRepository = isset($_POST['filterRepository']) ? $_POST['filterRepository'] : '';
$filterBranch = isset($_POST['filterBranch']) ? $_POST['filterBranch'] : '';
$filterUser = isset($_POST['filterUser']) ? $_POST['filterUser'] : '';
$filterDate = isset($_POST['filterDate']) ? $_POST['filterDate'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
            text-align: center;
        }
    </style>
</head>

<body class="text-center">

    <div class="container">
        <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>

        <!-- Add filter form -->
        <form action="" method="post">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="filterRepository">Repository:</label>
                    <input type="text" class="form-control" id="filterRepository" name="filterRepository" value="<?php echo htmlspecialchars($filterRepository); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="filterBranch">Branch:</label>
                    <input type="text" class="form-control" id="filterBranch" name="filterBranch" value="<?php echo htmlspecialchars($filterBranch); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="filterUser">User:</label>
                    <input type="text" class="form-control" id="filterUser" name="filterUser" value="<?php echo htmlspecialchars($filterUser); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="filterDate">Date:</label>
                    <input type="date" class="form-control" id="filterDate" name="filterDate" value="<?php echo htmlspecialchars($filterDate); ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </form>

        <?php
        // Specify the directory containing CSV files
        $csvDirectory = './';

        // Get a list of CSV files in the directory
        $csvFiles = glob($csvDirectory . '*.csv');

        // Display data from all CSV files
        if (empty($csvFiles)) {
            echo 'No CSV files found in the directory.';
        } else {
            foreach ($csvFiles as $csvFile) {
                csvToHtmlTable($csvFile, $filterRepository, $filterBranch, $filterUser, $filterDate);
                echo '<hr>'; // Add a horizontal line between tables
            }
        }
        ?>

        <p>
            <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
            <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
        </p>
    </div>

</body>
</html>

<?php
// Function to convert CSV data to an HTML table with filters
function csvToHtmlTable($csvFilePath, $filterRepository, $filterBranch, $filterUser, $filterDate)
{
    if (!file_exists($csvFilePath)) {
        echo "CSV file not found: $csvFilePath";
        return;
    }

    // Read CSV file
    $csvFile = fopen($csvFilePath, 'r');

    echo '<table class="table table-bordered mx-auto">';
    $firstRow = true; // Flag to identify the first row

    while (($data = fgetcsv($csvFile)) !== false) {
        // Filter rows based on filter criteria
        if (
            (empty($filterRepository) || stripos($data[0], $filterRepository) !== false) &&
            (empty($filterBranch) || stripos($data[1], $filterBranch) !== false) &&
            (empty($filterUser) || stripos($data[3], $filterUser) !== false) &&
            (empty($filterDate) ||  date('mm-dd-yyyy', strtotime($data[4])) == date('mm-dd-yyyy', strtotime($filterDate)))
        ) {
            echo '<tr>';

            foreach ($data as $value) {
                // Use <th> for the first row to display in bold font
                $cellTag = $firstRow ? 'th' : 'td';
                echo '<' . $cellTag . '>' . htmlspecialchars($value) . '</' . $cellTag . '>';
            }

            echo '</tr>';
        }

        // After processing the first row, set the flag to false
        $firstRow = false;
    }

    echo '</table>';

    fclose($csvFile);
}
?>
