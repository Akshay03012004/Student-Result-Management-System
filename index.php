<?php 
session_start();
require 'db.php'; 

// जर ॲडमिन लॉगिन नसेल तर लॉगिन पेजवर पाठवा
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// डॅशबोर्ड कॅल्क्युलेशन
$total_students = $conn->query("SELECT COUNT(id) as count FROM results")->fetch_assoc()['count'];
$passed = $conn->query("SELECT COUNT(id) as count FROM results WHERE math_marks >= 35 AND science_marks >= 35 AND english_marks >= 35")->fetch_assoc()['count'];
$failed = $total_students - $passed;
$avg_marks = round($conn->query("SELECT AVG(total_marks) as avg_marks FROM results")->fetch_assoc()['avg_marks'] ?? 0, 2);

// विद्यार्थी डिलीट करण्याचा कोड
if (isset($_GET['delete_id'])) {
    $conn->query("DELETE FROM results WHERE id = " . $_GET['delete_id']);
    echo "<script>window.location.href='index.php';</script>";
}

// नवीन विद्यार्थी ॲड करण्याचा कोड
if (isset($_POST['submit'])) {
    $name = $_POST['student_name'];
    $mname = $_POST['mothers_name']; // आईचं नाव
    $roll = $_POST['roll_no'];
    $math = $_POST['math'];
    $sci = $_POST['science'];
    $eng = $_POST['english'];

    if ($conn->query("SELECT * FROM results WHERE roll_no = '$roll'")->num_rows > 0) {
        echo "<div class='msg error'>⚠️ Roll Number $roll is already added!</div>";
    } else {
        $sql = "INSERT INTO results (student_name, mothers_name, roll_no, math_marks, science_marks, english_marks) VALUES ('$name', '$mname', '$roll', '$math', '$sci', '$eng')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.href='index.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Result Management - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%); min-height: 100vh; padding: 40px 20px; color: #333; }
        .container { background: white; padding: 40px; border-radius: 15px; max-width: 900px; margin: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        h2 { text-align: center; margin-bottom: 25px; color: #2c3e50; }
        .dashboard { display: flex; justify-content: space-between; gap: 15px; margin-bottom: 30px; }
        .card { flex: 1; background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-bottom: 4px solid #ccc; }
        .card h3 { font-size: 14px; color: #7f8c8d; text-transform: uppercase; margin-bottom: 10px; }
        .card p { font-size: 24px; font-weight: bold; color: #2c3e50; }
        .c-blue { border-color: #3498db; } .c-green { border-color: #2ecc71; } .c-red { border-color: #e74c3c; } .c-orange { border-color: #f39c12; }
        form { display: grid; gap: 15px; margin-top: 20px; }
        input { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; }
        button { background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%); color: white; border: none; padding: 14px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; text-align: center; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; }
        th { background: #4facfe; color: white; }
        .msg { text-align: center; margin-bottom: 20px; font-weight: 600; padding: 10px; border-radius: 5px; }
        .success { background-color: #e8f8f5; color: #2ecc71; border: 1px solid #2ecc71; }
        .error { background-color: #fdedec; color: #e74c3c; border: 1px solid #e74c3c; }
        .logout-btn { display: inline-block; background: #e74c3c; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; float: right; margin-bottom: 20px; }
        
        /* ॲक्शन बटन्सची डिझाईन */
        .btn-view { background: #3498db; color: white; padding: 6px 12px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 5px; font-size: 13px; }
        .btn-edit { background: #f39c12; color: white; padding: 6px 12px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 5px; font-size: 13px; }
        .btn-delete { background: #e74c3c; color: white; padding: 6px 12px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 13px; }
        .btn-view:hover { background: #2980b9; }
        .btn-edit:hover { background: #d35400; }
        .btn-delete:hover { background: #c0392b; }
    </style>
</head>
<body>

<div class="container">
    <a href="logout.php" class="logout-btn">Logout 🚪</a>
    <div style="clear: both;"></div>

    <h2>📊 Admin Analytics Dashboard</h2>

    <div class="dashboard">
        <div class="card c-blue"><h3>Total Students</h3><p><?php echo $total_students; ?></p></div>
        <div class="card c-green"><h3>Passed</h3><p><?php echo $passed; ?></p></div>
        <div class="card c-red"><h3>Failed</h3><p><?php echo $failed; ?></p></div>
        <div class="card c-orange"><h3>Avg Marks</h3><p><?php echo $avg_marks; ?></p></div>
    </div>

    <h2>🎓 Add New Result</h2>
    <form action="index.php" method="POST">
        <input type="text" name="student_name" placeholder="Student Name" required>
        <input type="text" name="mothers_name" placeholder="Mother's Name (For Security)" required>
        <input type="text" name="roll_no" placeholder="Roll Number" required>
        <input type="number" name="math" placeholder="Maths Marks (out of 100)" required max="100" min="0">
        <input type="number" name="science" placeholder="Science Marks (out of 100)" required max="100" min="0">
        <input type="number" name="english" placeholder="English Marks (out of 100)" required max="100" min="0">
        <button type="submit" name="submit">Save Result</button>
    </form>

    <h2 style="margin-top: 40px;">📋 Student Results Board</h2>
    <table>
        <tr>
            <th>Roll No</th>
            <th>Name</th>
            <th>Mother's Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM results");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $status = ($row["math_marks"]>=35 && $row["science_marks"]>=35 && $row["english_marks"]>=35) ? "<span style='color:green; font-weight:bold;'>Pass</span>" : "<span style='color:red; font-weight:bold;'>Fail</span>";
                echo "<tr>
                        <td><strong>".$row["roll_no"]."</strong></td>
                        <td>".$row["student_name"]."</td>
                        <td>".$row["mothers_name"]."</td>
                        <td>".$status."</td>
                        <td>
                            <a href='student_portal.php' class='btn-view' target='_blank'>View 📄</a>
                            <a href='update.php?id=".$row["id"]."' class='btn-edit'>Edit ✏️</a>
                            <a href='index.php?delete_id=".$row["id"]."' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this result?\")'>Delete 🗑️</a>
                        </td>
                      </tr>";
            }
        } else { echo "<tr><td colspan='5'>No results found</td></tr>"; }
        ?>
    </table>
</div>
</body>
</html>