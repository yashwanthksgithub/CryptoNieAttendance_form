<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
        }

        .contact-details {
            max-width: 400px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 10px;
            text-align: center;
        }

        p {
            text-align: center;
            margin: 0;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function copyEmail() {
            var emailElement = document.getElementById('email');
            var email = emailElement.textContent;
            var tempInput = document.createElement('input');
            tempInput.setAttribute('value', email);
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('Email copied to clipboard!');
        }
    </script>
</head>
<body>
    <?php
        // Connect to the database
        $conn = new mysqli('localhost', 'root', ' ', 'regdb');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Get the ID from the query string
        $contact_id = $_GET['id'];

        // Fetch contact details
        $stmt = $conn->prepare('SELECT id, name, course, email, phone FROM contact WHERE id = ?');
        $stmt->bind_param('s', $contact_id);
        $stmt->execute();
        $stmt->bind_result($id, $name, $course, $email, $phone);
        $stmt->fetch();
        $stmt->close();
        $conn->close();

        if ($id && $name && $course && $email && $phone) {
            echo '<h1>Contact Details</h1>';
            echo '<div class="contact-details">';
            echo '<h2>ID:</h2>';
            echo '<p>' . $id . '</p>';
            echo '<h2>Name:</h2>';
            echo '<p>' . $name . '</p>';
            echo '<h2>Course:</h2>';
            echo '<p>' . $course . '</p>';
            echo '<h2>Email:</h2>';
            echo '<p id="email">' . $email . '</p>';
            echo '<h2>Phone:</h2>';
            echo '<p>' . $phone . '</p>';
            echo '<div class="button-container">';
            echo '<button onclick="copyEmail()">Copy Email</button>';
            echo '<a href="wasye1.html?email=' . urlencode($email) . '"><button>Next page</button></a>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<p>Contact not found.</p>';
        }
    ?>
    <!-- <a href="index.html">Back to Home</a> -->
</body>
</html>
