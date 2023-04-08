<?php

$host_name = 'HostName';
$database = 'DatabaseName';
$user_name = 'DatabaseUsername';
$password = 'DatabasePassword';

$link = new mysqli($host_name, $user_name, $password, $database);

// Get input values from URL
$Name = mysqli_real_escape_string($link, $_GET['Name']);
$email = mysqli_real_escape_string($link, $_GET['Email']);
$amount = mysqli_real_escape_string($link, $_GET['Amount']);
$date = gmdate("d-m-y", strtotime(mysqli_real_escape_string($link, $_GET['Date'])));
$APIdate = gmdate("y-m-d", strtotime(mysqli_real_escape_string($link, $_GET['Date'])));
$time = gmdate("G:ia T", strtotime(mysqli_real_escape_string($link, $_GET['Time'])));
$barcode = mysqli_real_escape_string($link, uniqid());

// Generate unique barcode ID and image for each ticket
$barcodes = array();
$barcode_images = array();
for ($i = 0; $i < $amount; $i++) {
    $barcode = uniqid();
    $barcodes[] = $barcode;
    $barcode_image = "https://barcode.tec-it.com/barcode.ashx?data=".$barcode."&code=QRCode&dpi=96";
    $barcode_images[] = $barcode_image;
}
// Insert data into the database

$sql = "INSERT INTO exampleEvent (Uname, email, barcode, dateOfEvent, timeOfEvent) VALUES ('$Name', '$email', '$barcode', '$APIdate', '$time')";



if (mysqli_query($link, $sql)) {
    // Send email
    $to = $email;
    $subject = "Your Ticket Details";
    $message = '<html><body>';
    $message = "<p>Hello ".$Name.",\n\nThank you for purchasing ".$amount." ticket(s) for the ".$date." at ".$time."! Here are your unique QR Code(s):\n\n</p>";
    for ($i = 0; $i < $amount; $i++) {
        $message .= "<p>Ticket ".($i+1).": ".$barcodes[$i]."\n\n</p>";
        $message .= '<img src="https://barcode.tec-it.com/barcode.ashx?data=' . $barcode . '&code=QRCode&dpi=96" alt="Ticket Image" />';
    }
    $message .= "<p>Please present this QR Code at the entrance.\n\nBest regards,\nTicketing System Team</p>";
    $message .= '</body></html>';
    $headers = "From: ticketingservice@gmail.com" . "\r\n" .
               "Reply-To: ticketingservice@gmail.com" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();
    $boundary = md5(time());
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";
    $message = "--$boundary\r\n" .
               "Content-Type: text/html; charset=ISO-8859-1\r\n" .
               "Content-Transfer-Encoding: 7bit\r\n\r\n" .
               $message."\r\n";
    for ($i = 0; $i < $amount; $i++) {
        $message .= "--$boundary\r\n" .
                    "Content-Type: image/png\r\n" .
                    "Content-Transfer-Encoding: base64\r\n" .
                    "Content-Disposition: attachment; filename=\"ticket".($i+1).".png\"\r\n\r\n" .
                    chunk_split(base64_encode(file_get_contents($barcode_images[$i])))."\r\n";
    }
    $message .= "--$boundary--\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "New ticket(s) created and email sent!";
    } else {
        echo "Email sending failed.";
    }

} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($link);
}

// Close the database connection
mysqli_close($link);

?>