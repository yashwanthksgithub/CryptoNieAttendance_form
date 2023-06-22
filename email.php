<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

session_start();
include_once('config.php');
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Code for Signup
if(isset($_POST['submit'])){
    // Getting Post values
    $name = $_POST['username'];	
    $email = $_POST['email'];	
    $cnumber = $_POST['contactnumber'];	
    $loginpass = md5($_POST['password']); // Password is encrypted using MD5
    
    // Generating 6 Digit Random OTP
    $otp = mt_rand(100000, 999999);	
    
    // Query for validation of email-id
    $ret = "SELECT id FROM tblusers WHERE emailId = :uemail";
    $queryt = $dbh->prepare($ret);
    $queryt->bindParam(':uemail', $email, PDO::PARAM_STR);
    $queryt->execute();
    $results = $queryt->fetchAll(PDO::FETCH_OBJ);
    
    if($queryt->rowCount() == 0) {
        // Query for Inserting user data if email is not registered
        $emailverify = 0;
        $sql = "INSERT INTO tblusers (userName, emailId, ContactNumber, userPassword, emailOtp, isEmailVerify) 
                VALUES (:fname, :emaill, :cnumber, :hashedpass, :otp, :isactive)";
        $query = $dbh->prepare($sql);
        
        // Binding Post Values
        $query->bindParam(':fname', $name, PDO::PARAM_STR);
        $query->bindParam(':emaill', $email, PDO::PARAM_STR);
        $query->bindParam(':cnumber', $cnumber, PDO::PARAM_STR);
        $query->bindParam(':hashedpass', $loginpass, PDO::PARAM_STR);
        $query->bindParam(':otp', $otp, PDO::PARAM_STR);
        $query->bindParam(':isactive', $emailverify, PDO::PARAM_STR);
        $query->execute();
        
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId) {
            $_SESSION['emailid'] = $email;
            
            // Code for Sending Email
            $subject = "OTP Verification";
            
            // Instantiate PHPMailer
            // $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail = new PHPMailer();
            
            // Enable SMTP debugging
            $mail->SMTPDebug = 0;
            
            // Set the mailer to use SMTP
            $mail->isSMTP();
            
            // Specify SMTP server
            $mail->Host = 'your-smtp-server.com';
            
            // Enable SMTP authentication
            $mail->SMTPAuth = true;
            
            // SMTP username
            $mail->Username = 'yashwanthks586@gmail.com';
            
            // SMTP password
            $mail->Password = 'Kanakapura@123';
            
            // SMTP encryption
            $mail->SMTPSecure = 'tls';
            
            // SMTP port
            $mail->Port = 587;
            
            // Set the sender's email address and name
            $mail->setFrom('yashwanthks586@gmail.com', 'yashwanth ks');
            
            // Set the recipient's email address and name
            $mail->addAddress($email, $name);
            
            // Set email subject
            $mail->Subject = $subject;
            
            // Set email body
            $mail->isHTML(true);
            $mail->Body = "<html><body><div><div>Dear $name,</div><br/><br/>";
            $mail->Body .= "<div style='padding-top:8px;'>Thank you for registering with us. Your OTP for Account Verification is: $otp</div></div></body></html>";
            
            if($mail->send()) {
                echo "<script>window.location.href='verify-otp.php'</script>";
            } else {
                echo "<script>alert('Failed to send OTP. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";	
        }
    } else {
        echo "<script>alert('Email id is already associated with another account.');</script>";
    }
}
?>
