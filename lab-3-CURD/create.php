<?php
session_start(); // Start session for message persistence
include 'config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check for duplicate email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $email);
            if ($stmt->execute()) {
                $_SESSION['success'] = "User created successfully!"; // Store in session
                header("Location: index.php");
                exit();
            } else {
                $error = "Error creating user: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Add New User</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label>Name:</label>
            <input type="text" name="name" required><br>
            <label>Email:</label>
            <input type="email" name="email" required><br>
            <input type="submit" value="Add User" class="button">
        </form>
        <a href="index.php" class="button back">Back to User List</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>