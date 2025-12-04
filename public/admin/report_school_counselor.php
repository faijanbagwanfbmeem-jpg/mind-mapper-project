<?php
session_start();

// Initialize session array if not exists
if (!isset($_SESSION['school_counselor_list'])) {
    $_SESSION['school_counselor_list'] = [];
}

// Add new entry
if (isset($_POST['add'])) {
    $school = trim($_POST['school_name']);
    $counselor = trim($_POST['counselor_name']);

    if ($school !== "" && $counselor !== "") {
        $_SESSION['school_counselor_list'][] = [
            'school' => $school,
            'counselor' => $counselor,
            'created' => date("Y-m-d H:i:s")
        ];
    }
}

// Reset list
if (isset($_POST['reset'])) {
    $_SESSION['school_counselor_list'] = [];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Assign Counselor</title>
<style>
body {
    font-family: Arial;
    background: #f5f5f5;
    padding: 20px;
}
.box {
    width: 900px;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #ccc;
}
h2 {
    text-align: center;
}
form input {
    width: 48%;
    padding: 10px;
    margin: 5px 1%;
    border: 1px solid #ccc;
    border-radius: 6px;
}
.btn {
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 6px;
}
.add-btn { background: #007bff; color: white; }
.reset-btn { background: red; color: white; }
.print-btn { background: black; color: white; float: right; }

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
table th, table td {
    border: 1px solid black;
    padding: 10px;
    text-align: left;
}
@media print {
    .no-print { display: none; }
    body { background: white; }
}
</style>
</head>
<body>

<div class="box">

    <h2>Assign Counselor - School Wise</h2>

    <!-- Buttons -->
    <div class="no-print" style="margin-bottom:15px;">
        <button class="print-btn no-print" onclick="window.print()">Print</button>
    </div>

    <!-- Input Form -->
    <form method="POST" class="no-print">
        <input type="text" name="school_name" placeholder="Enter School Name" required>
        <input type="text" name="counselor_name" placeholder="Enter Counselor Name" required>

        <button type="submit" name="add" class="btn add-btn">Add</button>
        <button type="submit" name="reset" class="btn reset-btn">Reset</button>
    </form>

    <!-- Display Table -->
    <table>
        <tr>
            <th>#</th>
            <th>School Name</th>
            <th>Counselor Name</th>
            <th>Date Added</th>
        </tr>

        <?php
        $list = $_SESSION['school_counselor_list'];

        if (empty($list)) {
            echo "<tr><td colspan='4' style='text-align:center;'>No data added.</td></tr>";
        } else {
            $i = 1;
            foreach ($list as $item) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$item['school']}</td>
                        <td>{$item['counselor']}</td>
                        <td>{$item['created']}</td>
                    </tr>";
                $i++;
            }
        }
        ?>
    </table>

</div>

</body>
</html>
