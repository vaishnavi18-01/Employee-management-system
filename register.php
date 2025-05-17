<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password_raw = $_POST['password'];
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username already exists!";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $error = "Error while registering!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register</title>
<style>
  /* Reset */
  * {
    box-sizing: border-box;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #333;
  }

  .register-container {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    width: 350px;
    text-align: center;
  }

  h2 {
    margin-bottom: 25px;
    color: #4b0082;
    font-weight: 700;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
  }

  input[type="text"]:focus,
  input[type="password"]:focus {
    border-color: #764ba2;
    outline: none;
    box-shadow: 0 0 5px #764ba2;
  }

  .btn {
    width: 100%;
    background: #764ba2;
    color: white;
    padding: 14px 0;
    font-size: 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
    font-weight: 600;
  }

  .btn:hover {
    background: #5a347a;
  }

  .error {
    background: #ffdddd;
    color: #d8000c;
    border: 1px solid #d8000c;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
  }

  .login-link {
    margin-top: 20px;
    font-size: 14px;
    color: #666;
  }

  .login-link a {
    color: #764ba2;
    text-decoration: none;
    font-weight: 600;
  }

  .login-link a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>
<div class="register-container">
  <form method="post" novalidate>
    <h2>Register</h2>
    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <input 
      type="text" 
      name="username" 
      placeholder="Username" 
      required 
      value="<?= htmlspecialchars($username ?? '') ?>"
    />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit" class="btn">Register</button>
    <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
  </form>
</div>
</body>
</html>