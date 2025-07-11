<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $company  = trim($_POST['company']);
    $phone    = trim($_POST['phone']);
    $country  = trim($_POST['country']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // 2. Password strength validation
    $uppercase    = preg_match('@[A-Z]@', $password);
    $lowercase    = preg_match('@[a-z]@', $password);
    $number       = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    $lengthCheck  = strlen($password) >= 8;

    if (!$uppercase || !$lowercase || !$number || !$specialChars || !$lengthCheck) {
        echo "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
        exit();
    }

    // 3. Check for existing email
    $check = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Email already registered!";
        exit();
    }

    // 4. Hash password and register user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admins (name, email, company, phone, country, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $company, $phone, $country, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['admin_id'] = $stmt->insert_id;
        $_SESSION['admin_name'] = $name;
        $_SESSION['admin_company'] = $company;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_phone'] = $phone;
        $_SESSION['admin_country'] = $country;

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Registration failed!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-box {
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 420px;
    }

    .form-box h2 {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .form-box input {
      width: 100%;
      padding: 0.75rem;
      margin-bottom: 1rem;
      border: 2px solid #ccc;
      border-radius: 0.5rem;
      font-size: 1rem;
      transition: border 0.3s;
    }

    .form-box input.valid {
      border-color: #28a745;
      background-color: #eafaf1;
    }

    .form-box input.invalid {
      border-color: #dc3545;
      background-color: #fdecea;
    }

    .hint {
      font-size: 0.9rem;
      margin-top: -0.8rem;
      margin-bottom: 1rem;
      color: #999;
    }

    .hint.valid {
      color: #28a745;
    }

    .hint.invalid {
      color: #dc3545;
    }

    button {
      width: 100%;
      padding: 0.75rem;
      font-size: 1rem;
      font-weight: bold;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 0.5rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

  <form class="form-box" id="registerForm" method="POST" action="register.php">
    <h2>Create Account</h2>
    <input type="text" name="name" placeholder="Full Name" required />
    <input type="email" name="email" placeholder="Email Address" required />
    <input type="text" name="company" placeholder="Company" required />
   <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
  <select name="country_code" required style="width: 35%; border: 2px solid #ccc; border-radius: 0.5rem;">
    <option value="+961">🇱🇧 +961</option>
    <option value="+1">🇺🇸 +1</option>
    <option value="+44">🇬🇧 +44</option>
    <option value="+971">🇦🇪 +971</option>
    <option value="+20">🇪🇬 +20</option>
    <option value="+966">🇸🇦 +966</option>
    <option value="+962">🇯🇴 +962</option>
    <option value="+90">🇹🇷 +90</option>
    <option value="+33">🇫🇷 +33</option>
    <!-- Add more as needed -->
  </select>
  <input type="tel" name="phone" placeholder="Phone Number" required style="flex: 1; padding: 0.75rem; border: 2px solid #ccc; border-radius: 0.5rem;" />
</div>


    <input type="password" id="password" name="password" placeholder="Password" required />
    <div id="passHint" class="hint">At least 8 characters, one uppercase, one lowercase, one number, one symbol</div>

    <input type="password" id="confirm" name="confirm_password" placeholder="Confirm Password" required />
    <div id="confirmHint" class="hint">Passwords must match</div>

    <button type="submit">Register</button>
  </form>

  <script>
    const passwordInput = document.getElementById("password");
    const confirmInput = document.getElementById("confirm");
    const passHint = document.getElementById("passHint");
    const confirmHint = document.getElementById("confirmHint");

    function checkPassword() {
      const val = passwordInput.value;

      const isValid =
        val.length >= 8 &&
        /[A-Z]/.test(val) &&
        /[a-z]/.test(val) &&
        /[0-9]/.test(val) &&
        /[^A-Za-z0-9]/.test(val);

      passwordInput.className = isValid ? "valid" : "invalid";
      passHint.className = isValid ? "hint valid" : "hint invalid";
      return isValid;
    }

    function checkConfirm() {
      const isMatch = passwordInput.value === confirmInput.value && confirmInput.value !== "";
      confirmInput.className = isMatch ? "valid" : "invalid";
      confirmHint.className = isMatch ? "hint valid" : "hint invalid";
      return isMatch;
    }

    passwordInput.addEventListener("input", checkPassword);
    confirmInput.addEventListener("input", checkConfirm);

    document.getElementById("registerForm").addEventListener("submit", function (e) {
      if (!checkPassword() || !checkConfirm()) {
        alert("Please fix the password issues before submitting.");
        e.preventDefault();
      }
    });
  </script>

</body>
</html>
