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

if (!isset($_GET['id'])) {
    header('Location: user_list.php');
    exit;
}

$user_id = $_GET['id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gmail = $_POST['gmail'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];

    $sql = "UPDATE users SET username = ?, firstname = ?, lastname = ?, gmail = ?, role = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $username, $firstname, $lastname, $gmail, $role, $phone, $user_id);

    if ($stmt->execute()) {
        header('Location: user_list.php');
        exit;
    } else {
        echo "Error updating information.";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit User Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 90%;
        }
        h2 {
            text-align: center;
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="email"],
        select,
        button {
            width: 100%;
            padding: 15px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1.1em;
        }
        button {
            background-color: #007bff;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
            padding: 15px;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            background-color: #6c757d;
            color: white;
            margin-top: 20px;
            padding: 15px;
            font-size: 1.2em;
            width: 100%;
            text-align: center;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
        label {
            font-size: 1.1em;
            color: #555;
            margin-top: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit User Information</h2>
        <form method="POST">
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required placeholder="Username">
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required placeholder="First Name">
            <input type="text" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required placeholder="Last Name">
            <input type="email" name="gmail" value="<?php echo htmlspecialchars($user['gmail']); ?>" required placeholder="Email">
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required placeholder="Phone Number">

            <!-- Dropdown for selecting role -->
            <label for="role">Select Role:</label>
            <select name="role" id="role" required>
                <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>

            <button type="submit">Save Changes</button>
        </form>
        <a href="user_list.php"><button class="back-button">Back to User List</button></a>
    </div>
</body>
</html>
