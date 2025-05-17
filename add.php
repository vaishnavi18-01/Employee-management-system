<?php
// Include database connection
include 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
    $department = filter_var(trim($_POST['department']), FILTER_SANITIZE_STRING);
    $hire_date = $_POST['hire_date'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO employees (name, email, phone, department, hire_date) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sssss", $name, $email, $phone, $department, $hire_date);

    // Execute statement
    if ($stmt->execute()) {
        echo "Employee added successfully!";
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Add Employee</h2>
        <form method="post">
            <input name="Employee_ID" placeholder="ID" required><br>
            <input name="name" placeholder="Name" required><br>
            <input name="email" type="email" placeholder="Email" required><br>
            <input name="phone" placeholder="Phone"><br>
            <input name="department" placeholder="Department"><br>
            <input name="hire_date" type="date"><br>
            <button type="submit">Add</button>
        </form>
    </div>
</body>
</html>
