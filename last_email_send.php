<?php
// To Remove unwanted errors
error_reporting(0);

// Important Files (Please check your file path according to your folder structure)
require "PHPMailer-master/src/PHPMailer.php";
require "PHPMailer-master/src/SMTP.php";
require "PHPMailer-master/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentName = $_POST['studentName'];
    $branch = $_POST['branch'];
    $usn = $_POST['usn'];
    $courseName = $_POST['courseName'];
    $activityDetails = $_POST['activityDetails'];
    $documentProof = $_POST['documentProof'];
    $studentEmail = $_POST['studentEmail'];
    $facultyEmail = $_POST['facultyEmail'];


    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Enter your email ID
    $mail->Username = "2020ee_yashwanthks_b@nie.ac.in";
    $mail->Password = "Yashu@123";

    // Your email ID and Email Title
    $mail->setFrom($studentEmail, $studentName);

    $mail->addAddress($facultyEmail);

    // Create the email content
    $emailContent = "
    Attendance Form - Missed Class
    Student Name: $studentName
    Branch: $branch
    Branch: $usn
    Course Name: $courseName
    Activity Details: $activityDetails
    Document Proof Link: $documentProof
  ";


    $mail->Subject = "CryptoNieAttendanceForm";

    // You can change the Body Message according to your requirement!
    $mail->Body = $emailContent;
 
    if ($mail->send()) {
        echo "<script>window.location.href='welcome.php'</script>";
    } else {
        echo "<script>alert('Failed to send. Please try again.');</script>";
    }
} else {
    echo "Invalid request method. Please submit the form.";
}
?>