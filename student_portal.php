<?php
require 'db.php';
$student_data = null;
$error = "";

if (isset($_POST['search'])) {
    $roll_no = $_POST['roll_no'];
    $mothers_name = $_POST['mothers_name']; 
    
   
    $query = $conn->query("SELECT * FROM results WHERE roll_no = '$roll_no' AND mothers_name = '$mothers_name'");
    
    if ($query->num_rows > 0) {
        $student_data = $query->fetch_assoc();
    } else {
       
        $error = "❌ Invalid Details! Please check Roll Number & Mother's Name.";
    }
}

function getGrade($marks) {
    if ($marks >= 80) return "O (Outstanding)";
    if ($marks >= 70) return "A+ (Excellent)";
    if ($marks >= 60) return "A (Very Good)";
    if ($marks >= 50) return "B+ (Good)";
    if ($marks >= 40) return "B (Average)";
    if ($marks >= 35) return "P (Pass)";
    return "F (Fail)";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal - Secure Result View</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); min-height: 100vh; padding: 40px 20px; color: #333; }
        .search-box { background: white; padding: 30px; border-radius: 15px; max-width: 500px; margin: 0 auto 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); text-align: center; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; text-align: center; }
        button { width: 100%; background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%); color: white; border: none; padding: 14px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; }
        .error { color: #e74c3c; margin-top: 15px; font-weight: bold; }
        .marksheet-container { background: white; max-width: 800px; margin: auto; padding: 40px; border: 2px solid #333; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .student-details { margin-bottom: 30px; } .student-details table { width: 100%; font-size: 16px; }
        .marks-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; text-align: center; }
        .marks-table th, .marks-table td { border: 1px solid #333; padding: 12px; }
        .marks-table th { background: #f0f0f0; }
        .print-btn { display: block; width: 200px; margin: 30px auto 0; padding: 10px; background: #3498db; color: white; text-align: center; text-decoration: none; border-radius: 5px; border: none; cursor:pointer;}
        @media print { .search-box, .print-btn { display: none; } body { background: white; padding: 0; } .marksheet-container { border: none; } }
    </style>
</head>
<body>

<div class="search-box">
    <h2>🎓 Secure Result Portal</h2>
    <p style="margin-bottom: 15px; color: #666;">Enter Details to view your Grade Card</p>
    
    <form method="POST" action="">
        <input type="text" name="roll_no" placeholder="Enter Roll Number" required>
        
        <input type="text" name="mothers_name" placeholder="Enter Mother's Name" required>
        
        <button type="submit" name="search">View Result</button>
    </form>

    <?php if($error != "") echo "<p class='error'>$error</p>"; ?>
</div>

<?php if ($student_data != null) { 
    $is_pass = ($student_data['math_marks']>=35 && $student_data['science_marks']>=35 && $student_data['english_marks']>=35);
?>
<div class="marksheet-container">
    <div class="header">
        <h1>STATEMENT OF GRADES</h1>
        <p>B.Tech Computer Science & Engineering</p>
    </div>
    <div class="student-details">
        <table>
            <tr>
                <td><strong>Student Name:</strong> <?php echo strtoupper($student_data['student_name']); ?></td>
                <td style="text-align: right;"><strong>Roll Number:</strong> <?php echo $student_data['roll_no']; ?></td>
            </tr>
            <tr>
                <td><strong>Mother's Name:</strong> <?php echo strtoupper($student_data['mothers_name']); ?></td>
            </tr>
        </table>
    </div>
    <table class="marks-table">
        <tr><th>Course Title</th><th>Marks Obtained</th><th>Grade</th></tr>
        <tr><td>Mathematics</td><td><?php echo $student_data['math_marks']; ?></td><td><?php echo getGrade($student_data['math_marks']); ?></td></tr>
        <tr><td>Science</td><td><?php echo $student_data['science_marks']; ?></td><td><?php echo getGrade($student_data['science_marks']); ?></td></tr>
        <tr><td>English</td><td><?php echo $student_data['english_marks']; ?></td><td><?php echo getGrade($student_data['english_marks']); ?></td></tr>
        <tr style="font-weight: bold; background: #f9f9f9;"><td>Total</td><td><?php echo $student_data['total_marks']; ?> / 300</td><td>--</td></tr>
    </table>
    <button class="print-btn" onclick="window.print()">🖨️ Print Grade Card</button>
</div>
<?php } ?>

</body>
</html>
