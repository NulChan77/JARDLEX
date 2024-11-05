<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'user_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gmail = $_POST['gmail'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, firstname, lastname, gmail, password, phone) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $username, $firstname, $lastname, $gmail, $hashed_password, $phone);

    if ($stmt->execute()) {
        header('Location: user_list.php'); 
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add New User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 50px; /* เพิ่ม padding ให้กับคอนเทนเนอร์ */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-width: 600px; /* เพิ่มขนาดความกว้าง */
            margin: auto;
        }
        h2 {
            text-align: center;
            font-size: 1.8em; /* เพิ่มขนาดหัวข้อ */
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        button {
            width: 100%;
            padding: 15px; /* เพิ่ม padding ให้ใหญ่ขึ้น */
            font-size: 1.1em; /* เพิ่มขนาดฟอนต์ */
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            font-size: 1.2em; /* เพิ่มขนาดฟอนต์ของปุ่ม */
            padding: 15px;
        }
        button:hover {
            background-color: #218838;
        }
        .back-button {
            background-color: #6c757d;
            color: white;
            width: 100%;
            text-align: center;
            margin-top: 15px;
            padding: 15px;
            font-size: 1.1em;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Add User</h2>
    <form method="POST" action="add_user.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="firstname" placeholder="First Name" required>
        <input type="text" name="lastname" placeholder="Last Name" required>
        <input type="email" name="gmail" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Add User</button>
    </form>
    <a href="user_list.php"><button class="back-button">Back to User List</button></a>
</div>
</body>
</html>
