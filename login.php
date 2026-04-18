<?php
session_start();
include 'db.php';

// जर एडमिन आधीच लॉगिन असेल, तर त्याला थेट index.php वर पाठवा
if(isset($_SESSION['admin_logged_in'])){
    header("Location: index.php");
    exit();
}

$error = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // डेटाबेसमध्ये युझरनेम आणि पासवर्ड चेक करणे
    $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: index.php"); // लॉगिन झाल्यावर डॅशबोर्डवर जा
        exit();
    } else {
        $error = "❌ Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); height: 100vh; display: flex; justify-content: center; align-items: center; }
        .login-box { background: white; padding: 40px; border-radius: 15px; width: 100%; max-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); text-align: center; }
        h2 { margin-bottom: 20px; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; }
        input:focus { border-color: #fda085; outline: none; }
        button { width: 100%; background: linear-gradient(to right, #ff758c 0%, #ff7eb3 100%); color: white; border: none; padding: 12px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        button:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 117, 140, 0.4); }
        .error { color: #e74c3c; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔒 Admin Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if($error != "") echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>