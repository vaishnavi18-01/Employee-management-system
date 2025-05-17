<?php
include 'db.php';

$search = $_GET['query'] ?? '';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM employees WHERE name LIKE ? OR email LIKE ? OR department LIKE ?");
    $like = "%$search%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['department']}</td>
            <td>{$row['hire_date']}</td>
            <td>
                <a href='edit.php?id={$row['id']}'>Edit</a> |
                <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No search term provided.</td></tr>";
}
?>