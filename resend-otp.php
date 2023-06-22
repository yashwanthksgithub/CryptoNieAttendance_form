<?php session_start();
include_once('config.php');
require "PHPMailer-master/src/PHPMailer.php";
require "PHPMailer-master/src/SMTP.php";
require "PHPMailer-master/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
//Code for Resend
if(isset($_POST['resend'])){
//Getting Post values
$email=$_POST['email'];	
//Generating 6 Digit Random OTP
$otp= mt_rand(100000, 999999);	
// Query for validation of  email-id
$ret="SELECT id,isEmailVerify FROM  tblusers where (emailId=:uemail)";
$queryt = $dbh -> prepare($ret);
$queryt->bindParam(':uemail',$email,PDO::PARAM_STR);
$queryt -> execute();
$results = $queryt -> fetchAll(PDO::FETCH_OBJ);
if($queryt -> rowCount() > 0)
{
foreach ($results as $result) {
$verifystatus=$result->isEmailVerify;}	

//if email already verified
if($verifystatus=='1'){
echo "<script>alert('Email already verified. No need to verify again.');</script>";
} else{
$_SESSION['emailid']=$email;
$_SESSION['otp']=$otp;

$sql="update tblusers set emailOtp=:otp where emailId=:emailid";
$query = $dbh->prepare($sql);
// Binding Post Values
$query->bindParam(':emailid',$email,PDO::PARAM_STR);
$query->bindParam(':otp',$otp,PDO::PARAM_STR);
$query->execute();	
//Code for Sending Email
$mail = new PHPMailer(true);
            
// Enable SMTP authentication
$mail->SMTPAuth = true;

// Enable SMTP debugging
// $mail->SMTPDebug = 0;	

// Set the mailer to use SMTP
$mail->isSMTP();

// Specify SMTP server
$mail->Host = 'smtp.gmail.com';

$mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;

// SMTP username
$mail->Username = '2020ee_yashwanthks_b@nie.ac.in';

// SMTP password
$mail->Password = 'Yashu@123';

// SMTP encryption
$mail->SMTPSecure = 'tls';

// SMTP port
$mail->Port = 587;

// Set the sender's email address and name
$mail->setFrom('2020ee_yashwanthks_b@nie.ac.in', 'Yashwanth ks');

// Set the recipient's email address and name
$mail->addAddress($email);

// Set email subject
$mail->Subject = "OTP Verification";;

// Set email body
// $mail->isHTML(true);
// $mail->Body = "<html><body><div><div>Dear $name,</div><br/><br/>";
$mail->Body = "Hello,Your account registration is successfully done! Now activate your account with OTP {$otp}. which now recently sent";
echo"hello";

if($mail->send()) {
	echo "<script>window.location.href='verify-otp.php'</script>";
} else {
	echo "<script>alert('Failed to send OTP. Please try again.');</script>";
}

}
}else {
echo "<script>alert('Email id not registered yet');</script>";
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Courgette|Pacifico:400,700">
<title>User Registration with email otp verification in PHP</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
body {
	color: #999;
	background: #e2e2e2;
	font-family: 'Roboto', sans-serif;
}
.form-control {
	min-height: 41px;
	box-shadow: none;
	border-color: #e1e1e1;
}
.form-control:focus {
	border-color: #00cb82;
}	
.form-control, .btn {        
	border-radius: 3px;
}
.form-header {
	margin: -30px -30px 20px;
	padding: 30px 30px 10px;
	text-align: center;
	background: #00cb82;
	border-bottom: 1px solid #eee;
	color: #fff;
}
.form-header h2 {
	font-size: 34px;
	font-weight: bold;
	margin: 0 0 10px;
	font-family: 'Pacifico', sans-serif;
}
.form-header p {
	margin: 20px 0 15px;
	font-size: 17px;
	line-height: normal;
	font-family: 'Courgette', sans-serif;
}
.signup-form {
	width: 390px;
	margin: 0 auto;	
	padding: 30px 0;	
}
.signup-form form {
	color: #999;
	border-radius: 3px;
	margin-bottom: 15px;
	background: #f0f0f0;
	box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	padding: 30px;
}
.signup-form .form-group {
	margin-bottom: 20px;
}		
.signup-form label {
	font-weight: normal;
	font-size: 14px;
}
.signup-form input[type="checkbox"] {
	position: relative;
	top: 1px;
}
.signup-form .btn {        
	font-size: 16px;
	font-weight: bold;
	background: #00cb82;
	border: none;
	min-width: 200px;
}
.signup-form .btn:hover, .signup-form .btn:focus {
	background: #00b073 !important;
	outline: none;
}
.signup-form a {
	color: #00cb82;		
}
.signup-form a:hover {
	text-decoration: underline;
}
</style>
</head>
<body>
<div class="signup-form">
    <form  method="post">
		<div class="form-header">
			<h2>Resend OTP</h2>
		</div>

        <div class="form-group">
			<label>Email Address</label>
        	<input type="email" class="form-control" name="email" required="required">
        </div>

		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-block btn-lg" name="resend">Resend</button>
		</div>	
    </form>
	<div class="text-center small">Already have an account? <a href="login.php">Login here</a></div>
</div>
</body>
</html>