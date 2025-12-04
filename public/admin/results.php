<?php
require_once "../../app/db.php";
include "inc/header.php";

// Fetch all results with student name
$sql = "
SELECT r.id, r.student_id, r.exam_type, r.score, r.max_score, r.created,
       u.username,
       si.full_name
FROM exam_results r
LEFT JOIN users u ON u.id = r.student_id
LEFT JOIN student_info si ON si.user_id = r.student_id
ORDER BY r.id DESC
";

$results = $mysqli->query($sql);
?>

<style>
.results-container {
    background: #fff;
    padding: 25px;
    margin: 20px;
    border-radius: 8px;
}

.results-title {
    font-size: 26px;
    margin-bottom: 15px;
    font-weight: bold;
}

.results-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.results-table th {
    background: #e9e9e9;
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
    font-weight: bold;
}

.results-table td {
    padding: 9px;
    border: 1px solid #ddd;
}

.results-table tr:nth-child(even) {
    background: #f9f9f9;
}

.view-btn {
    color: blue;
    text-decoration: underline;
}

</style>

<div class="results-container">
    <div class="results-title">All Student Results</div>

    <table class="results-table">
        <tr>
            <th>ID</th>
            <th>Student</th>
            <th>Exam Type</th>
            <th>Score</th>
            <th>Max Score</th>
            <th>Details</th>
            <th>Date</th>
        </tr>

        <?php while ($row = $results->fetch_assoc()): ?>
        <tr>
            <td><?= $row["id"] ?></td>

            <td>
                <?= htmlspecialchars($row["full_name"] ?: $row["username"]) ?>
            </td>

            <td><?= $row["exam_type"] ?></td>
            <td><?= $row["score"] ?></td>
            <td><?= $row["max_score"] ?></td>
            
            <td>
                <a class="view-btn" href="view_result.php?id=<?= $row['id'] ?>">View</a>
            </td>

            <td><?= $row["created"] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include "inc/footer.php"; ?>
