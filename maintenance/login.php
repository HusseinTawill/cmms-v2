<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
         $_SESSION['admin_id'] = $admin['id']; 
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_company'] = $admin['company'];
        $_SESSION['admin_email'] = $admin['email'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register {
            background-color: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .register input {
            width: 100%;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .register input:focus {
            border-color: #007bff;
            outline: none;
        }

        .register button {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .register button:hover {
            background-color: #0056b3;
        }

        .register .small-link {
            display: block;
            text-align: right;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .register a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .register a:hover {
            text-decoration: underline;
        }

        .register p {
            margin-top: 1rem;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <form action="login.php" class="register" method="POST">
        <h2 style="margin-bottom: 10px;">Login</h2>
        <input type="email" name="email" placeholder="Email Address" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <a href="forgot-password.html" class="small-link">Forgot Password?</a>
        <button type="submit" class="register">Login</button>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>
