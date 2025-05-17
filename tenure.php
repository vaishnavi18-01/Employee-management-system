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

// Calculate tenure
$hire_date = new DateTime($employee['hire_date']);
$current_date = new DateTime();
$interval = $hire_date->diff($current_date);
$years = $interval->y;
$months = $interval->m;
$days = $interval->d;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Tenure</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Employee Tenure: <?= htmlspecialchars($employee['name']) ?></h2>
    <canvas id="tenureChart" width="400" height="200"></canvas>
    <script>
        var ctx = document.getElementById('tenureChart').getContext('2d');
        var tenureChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Years', 'Months', 'Days'],
                datasets: [{
                    label: 'Tenure',
                    data: [<?= $years ?>, <?= $months ?>, <?= $days ?>],
                    backgroundColor: ['#4CAF50', '#FFC107', '#2196F3'],
                    borderColor: ['#388E3C', '#FF9800', '#1976D2'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
