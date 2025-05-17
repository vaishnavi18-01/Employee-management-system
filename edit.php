<?php
// Include database connection
include 'db.php';

// Retrieve employee ID from URL parameter
$employeeId = $_GET['id'] ?? null;
if (!$employeeId) {
    die("Employee ID is required.");
}

// Fetch employee details from the database
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $employeeId);
$stmt->execute();
$employee = $stmt->get_result()->fetch_assoc();
$stmt->close();

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
        die("Invalid email format.");
    }

    // Prepare and execute update query
    $stmt = $conn->prepare("UPDATE employees SET name = ?, email = ?, phone = ?, department = ?, hire_date = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $email, $phone, $department, $hire_date, $employeeId);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!-- HTML Form for Editing Employee -->
<form method="post"><head>
    <link rel="stylesheet" href="style.css">
    <h2>Edit Employee</h2>
    <label for="name">Name:</label>
    <input id="name" name="name" type="text" value="<?= htmlspecialchars($employee['name']) ?>" required><br>

    <label for="email">Email:</label>
    <input id="email" name="email" type="email" value="<?= htmlspecialchars($employee['email']) ?>" required><br>

    <label for="phone">Phone:</label>
    <input id="phone" name="phone" type="text" value="<?= htmlspecialchars($employee['phone']) ?>"><br>

    <label for="department">Department:</label>
    <input id="department" name="department" type="text" value="<?= htmlspecialchars($employee['department']) ?>"><br>

    <label for="hire_date">Hire Date:</label>
    <input id="hire_date" name="hire_date" type="date" value="<?= htmlspecialchars($employee['hire_date']) ?>"><br>

    <button type="submit">Update</button></head>
</form>
