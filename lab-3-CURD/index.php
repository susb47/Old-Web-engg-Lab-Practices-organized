<?php
session_start(); // Start session for message persistence
include 'config.php';

// Handle success message from session
$success = isset($_SESSION['success']) ? htmlspecialchars($_SESSION['success']) : '';
unset($_SESSION['success']); // Clear session variable after use

$stmt = $conn->prepare("SELECT id, name, email FROM users");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>User Management</h2>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <p><a href="create.php" class="button add">Add New User</a></p>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["id"]) . "</td>
                            <td>" . htmlspecialchars($row["name"]) . "</td>
                            <td>" . htmlspecialchars($row["email"]) . "</td>
                            <td>
                                <a href='update.php?id=" . $row["id"] . "' class='button edit'>Edit</a>
                                <a href='delete.php?id=" . $row["id"] . "' class='button delete' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found</td></tr>";
            }
            $stmt->close();
            ?>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>