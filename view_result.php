<?php
session_start();
require 'db.php';


if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}


if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$query = $conn->query("SELECT * FROM results WHERE id = $id");

if ($query->num_rows == 0) {
    die("Student not found!");
}

$student = $query->fetch_assoc();


$is_pass = ($student['math_marks'] >= 35 && $student['science_marks'] >= 35 && $student['english_marks'] >= 35);
$status = $is_pass ? "PASS" : "FAIL";
$percentage = round(($student['total_marks'] / 300) * 100, 2);


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
    <title>Grade Card - <?php echo $student['student_name']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .marksheet-container { background: white; max-width: 800px; margin: auto; padding: 40px; border: 2px solid #333; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.2); }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #2c3e50; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; font-size: 16px; color: #555; font-weight: bold; }
        .student-details { margin-bottom: 30px; }
        .student-details table { width: 100%; font-size: 16px; }
        .student-details td { padding: 5px; }
        .marks-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .marks-table th, .marks-table td { border: 1px solid #333; padding: 12px; text-align: center; }
        .marks-table th { background: #f0f0f0; }
        .result-summary { border: 2px solid #333; padding: 15px; text-align: center; font-size: 18px; font-weight: bold; background: #fafafa; }
        .status-pass { color: green; }
        .status-fail { color: red; }
        .print-btn { display: block; width: 200px; margin: 30px auto 0; padding: 10px; background: #3498db; color: white; text-align: center; text-decoration: none; font-size: 18px; border-radius: 5px; cursor: pointer; border: none; }
        .print-btn:hover { background: #2980b9; }
        
        
        @media print {
            .print-btn { display: none; }
            body { background: white; padding: 0; }
            .marksheet-container { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<div class="marksheet-container">
    <div class="header">
        <h1>STATEMENT OF GRADES</h1>
        <p>B.Tech Computer Science & Engineering</p>
    </div>

    <div class="student-details">
        <table>
            <tr>
                <td><strong>Student Name:</strong> <?php echo strtoupper($student['student_name']); ?></td>
                <td style="text-align: right;"><strong>Roll Number:</strong> <?php echo $student['roll_no']; ?></td>
            </tr>
        </table>
    </div>

    <table class="marks-table">
        <tr>
            <th>Course Title</th>
            <th>Max Marks</th>
            <th>Marks Obtained</th>
            <th>Awarded Grade</th>
        </tr>
        <tr>
            <td style="text-align: left;">Mathematics</td>
            <td>100</td>
            <td><?php echo $student['math_marks']; ?></td>
            <td><?php echo getGrade($student['math_marks']); ?></td>
        </tr>
        <tr>
            <td style="text-align: left;">Science</td>
            <td>100</td>
            <td><?php echo $student['science_marks']; ?></td>
            <td><?php echo getGrade($student['science_marks']); ?></td>
        </tr>
        <tr>
            <td style="text-align: left;">English</td>
            <td>100</td>
            <td><?php echo $student['english_marks']; ?></td>
            <td><?php echo getGrade($student['english_marks']); ?></td>
        </tr>
        <tr style="font-weight: bold; background: #f9f9f9;">
            <td style="text-align: right;">Total</td>
            <td>300</td>
            <td><?php echo $student['total_marks']; ?></td>
            <td>--</td>
        </tr>
    </table>

    <div class="result-summary">
        Percentage: <?php echo $percentage; ?>% &nbsp; | &nbsp; 
        Result: <span class="<?php echo $is_pass ? 'status-pass' : 'status-fail'; ?>"><?php echo $status; ?></span>
    </div>

    <button class="print-btn" onclick="window.print()">🖨️ Print Grade Card</button>
</div>

</body>
</html>
