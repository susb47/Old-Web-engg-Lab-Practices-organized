<?php
session_start();
include 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$success = '';

// Fetch current data
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    die("User not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check for duplicate email (excluding current user)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            // Update user
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "User updated successfully!";
                header("Location: index.php");
                exit();
            } else {
                $error = "Error updating user: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container edit-page">
        <h2>Edit User</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required><br>
            <input type="submit" value="Update User" class="button">
        </form>
        <a href="index.php" class="button back">Back to User List</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>