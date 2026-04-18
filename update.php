<?php
session_start();
require 'db.php';

// जर एडमिन लॉगिन नसेल तर परत पाठवा
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// URL मधून विद्यार्थ्याचा ID घेणे
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

// डेटाबेसमधून जुनी माहिती काढणे
$query = $conn->query("SELECT * FROM results WHERE id = $id");
$student = $query->fetch_assoc();

// जेव्हा 'Update' बटण दाबलं जाईल
if (isset($_POST['update'])) {
    $name = $_POST['student_name'];
    $mname = $_POST['mothers_name'];
    $roll = $_POST['roll_no'];
    $math = $_POST['math'];
    $sci = $_POST['science'];
    $eng = $_POST['english'];

    // माहिती डेटाबेसमध्ये अपडेट (Update) करण्याची SQL Query
    $sql = "UPDATE results SET 
            student_name='$name', 
            mothers_name='$mname', 
            roll_no='$roll', 
            math_marks='$math', 
            science_marks='$sci', 
            english_marks='$eng' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Result Updated Successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Result</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%); font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 10px; width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        label { font-weight: bold; font-size: 14px; color: #555; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-size: 15px; }
        input:focus { border-color: #3498db; outline: none; }
        button { width: 100%; padding: 12px; background: #f39c12; color: white; border: none; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; }
        button:hover { background: #e67e22; }
        .back-btn { display: block; text-align: center; margin-top: 15px; color: #e74c3c; text-decoration: none; font-weight: bold; }
        .back-btn:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>✏️ Edit Student Result</h2>
    <form method="POST">
        <label>Student Name:</label>
        <input type="text" name="student_name" value="<?php echo $student['student_name']; ?>" required>
        
        <label>Mother's Name:</label>
        <input type="text" name="mothers_name" value="<?php echo $student['mothers_name']; ?>" required>
        
        <label>Roll No:</label>
        <input type="text" name="roll_no" value="<?php echo $student['roll_no']; ?>" required>
        
        <label>Maths Marks:</label>
        <input type="number" name="math" value="<?php echo $student['math_marks']; ?>" max="100" min="0" required>
        
        <label>Science Marks:</label>
        <input type="number" name="science" value="<?php echo $student['science_marks']; ?>" max="100" min="0" required>
        
        <label>English Marks:</label>
        <input type="number" name="english" value="<?php echo $student['english_marks']; ?>" max="100" min="0" required>
        
        <button type="submit" name="update">Save Changes</button>
        <a href="index.php" class="back-btn">❌ Cancel & Go Back</a>
    </form>
</div>

</body>
</html>