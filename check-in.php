<?php
// Create a connection to the SQL database
$host_name = 'HostName';
$database = 'DatabaseName';
$user_name = 'DatabaseUsername';
$password = 'DatabasePassword';

$link = new mysqli($host_name, $user_name, $password, $database);

// Define the function to check in a guest
function check_in($barcode) {

    global $link;

    date_default_timezone_set('Europe/London');
    $currentDate = date("Y-m-d");

    // Query the database for the barcode
    $sql = "SELECT * FROM exampleEvent WHERE barcode = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the guest is not found, show an error message
    if ($result->num_rows == 0) {
        return "Invalid barcode";
    }
    else {
        $row = $result->fetch_assoc();

        // If the guest has already been checked in, show a message
        if ($row["checked_in"] == 1) {
            $name = $row["Uname"];
            return "Guest already checked in, their name is: $name";
        }
        else {
            if($row["dateOfEvent"] == $currentDate){
                 // Update the guest's record in the database
                 $sql = "UPDATE exampleEvent SET checked_in = 1 WHERE barcode = ?";
                 $stmt = $link->prepare($sql);
                 $stmt->bind_param("s", $barcode);
                 $stmt->execute();
                 return "Guest checked in successfully!";
            }
            else{
                return "Check-in date not valid right now: " . $row["dateOfEvent"];
            }
        }
    }
}

// Check if barcode is provided in GET request
if (isset($_GET["barcode"])) {
    $barcode = $_GET["barcode"];
    $result = check_in($barcode);
    echo $result;
}
else {
    echo "Barcode not provided";
}

// Close database connection
$link->close();
?>

