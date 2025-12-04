<?php
// public/admin/students.php
// Clean, robust admin students list
// - No duplicate functions
// - Handles nulls safely (no htmlspecialchars() deprecation warnings)
// - Inline counselor status update (posts to same page)
// - Search / Filter / Sort / Export CSV UI (export endpoint separate)
// - Clean layout with responsive table

require_once "../../app/db.php"; // must create $mysqli
if (session_status() === PHP_SESSION_NONE) session_start();

// If not logged-in admin, optionally redirect (depends on your auth system)
// if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

// Handle an inline counselor status update (form posts back to this page)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $sid = intval($_POST['student_id']);
    $status = $_POST['counselor_sent'] ?? 'Pending';
    $allowed = ['Pending', 'Attended', 'Not Attended'];
    if (!in_array($status, $allowed)) $status = 'Pending';

    $u = $mysqli->prepare("UPDATE student_info SET counselor_sent=? WHERE id=?");
    $u->bind_param("si", $status, $sid);
    $u->execute();
    $u->close();

    // redirect to avoid form re-submit
    header("Location: students.php");
    exit;
}

// Build filters, search, sort
$search = trim($_GET['search'] ?? '');
$filter = $_GET['filter'] ?? '';
$sort = $_GET['sort'] ?? 'created_desc';

// Base SQL - use aliases for clarity
$sql = "SELECT si.*, u.username, u.email,
        (SELECT score FROM exam_results WHERE student_id = si.user_id ORDER BY id DESC LIMIT 1) AS last_score,
        (SELECT exam_type FROM exam_results WHERE student_id = si.user_id ORDER BY id DESC LIMIT 1) AS last_exam
        FROM student_info si
        LEFT JOIN users u ON u.id = si.user_id";

$where = [];
$params = [];
$types = "";

// Search: match id OR name OR username OR email
if ($search !== '') {
    $where[] = "(si.id = ? OR si.full_name LIKE CONCAT('%',?,'%') OR u.username LIKE CONCAT('%',?,'%') OR u.email LIKE CONCAT('%',?,'%'))";
    // use id when numeric, otherwise pass as string for the rest
    $params[] = (is_numeric($search) ? intval($search) : 0);
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
    $types .= "isss";
}

// Counselor filter
if (in_array($filter, ['Pending','Attended','Not Attended'])) {
    $where[] = "si.counselor_sent = ?";
    $params[] = $filter;
    $types .= "s";
}

// IQ/EQ filters kept commented (you previously removed IQ/EQ from UI). Uncomment if needed.
// if ($filter === 'high_iq') $where[] = "si.iq_percent >= 70";
// ...

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Sort mapping
switch ($sort) {
    case 'name_asc': $sql .= " ORDER BY si.full_name ASC"; break;
    case 'name_desc': $sql .= " ORDER BY si.full_name DESC"; break;
    case 'date_asc': $sql .= " ORDER BY si.created ASC"; break;
    default: $sql .= " ORDER BY si.created DESC"; break;
}

// Prepare & execute
if ($params) {
    $stmt = $mysqli->prepare($sql);
    // bind dynamically
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $students = $stmt->get_result();
} else {
    $students = $mysqli->query($sql);
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Panel - Students</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
/* Basic admin theme */
body {font-family: Inter, Arial, sans-serif; margin:0; background:#f0f2f5; color:#222;}
.header {height:60px; background:#0b0b0b; color:#fff; display:flex; align-items:center; justify-content:space-between; padding:0 20px;}
.header a {color:#fff; text-decoration:none; font-weight:600;}
.container {display:flex; min-height:calc(100vh - 60px);}
.sidebar {width:220px; background:#222; color:#fff; padding:20px 10px;}
.sidebar a {display:block; color:#fff; padding:10px 14px; text-decoration:none; border-radius:6px;}
.sidebar a:hover {background:#111;}
.main {flex:1; padding:24px;}

/* controls */
.controls {display:flex; gap:10px; flex-wrap:wrap; margin-bottom:12px; align-items:center;}
.controls input, .controls select {padding:8px 10px; border-radius:6px; border:1px solid #ccc;}
.controls button {padding:8px 12px; border-radius:6px; border:0; background:#000; color:#fff; cursor:pointer;}

/* table */
.card {background:#fff; border-radius:8px; padding:18px; box-shadow:0 6px 20px rgba(0,0,0,0.06);}
.table {width:100%; border-collapse:collapse; margin-top:8px;}
.table th, .table td {padding:12px 10px; border-bottom:1px solid #eee; text-align:left; vertical-align:middle;}
.table thead th {background:#fafafa; font-weight:700;}
.small-btn {padding:8px 10px; background:#111; color:#fff; border-radius:6px; text-decoration:none; border:0; cursor:pointer;}
.small-btn.secondary {background:#4b5563;}
.select-inline {padding:6px 8px; border-radius:6px;}

/* responsive */
@media (max-width:900px){
  .sidebar{display:none;}
  .controls{flex-direction:column; align-items:stretch;}
  .table th:nth-child(3), .table td:nth-child(3) {display:none;}
}
.notice {color:#b91c1c; font-weight:600; margin:6px 0;}
.small {font-size:13px; color:#666;}
</style>
</head>
<body>

<div class="header">
    <div style="font-weight:700;">Admin Panel - MindMapper</div>
    <div><a href="../index.php" target="_blank">View Site</a> &nbsp; <a href="logout.php">Logout</a></div>
</div>

<div class="container">
    <div class="sidebar">
        <div style="font-weight:800; padding:6px 12px; margin-bottom:10px;">Menu</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="students.php" style="background:#111;">Students</a>
        <a href="iq_questions.php">IQ Questions</a>
        <a href="eq_questions.php">EQ Questions</a>
        <a href="results.php">Exam Results</a>
        <a href="reports.php">Reports</a>
    </div>

    <div class="main">
        <h1 style="margin:0 0 12px 0;">Students</h1>

        <div class="controls">
            <form method="get" style="display:flex; gap:8px;">
                <input type="text" name="search" placeholder="Search by ID / name / username / email" value="<?= htmlspecialchars($search ?? '') ?>">
                <button type="submit">Search</button>
            </form>

            <form method="get" style="display:flex; gap:8px;">
                <select name="filter" class="select-inline">
                    <option value="">-- Filter --</option>
                    <option value="Pending" <?= $filter === 'Pending' ? 'selected' : '' ?>>Counselor: Pending</option>
                    <option value="Attended" <?= $filter === 'Attended' ? 'selected' : '' ?>>Counselor: Attended</option>
                    <option value="Not Attended" <?= $filter === 'Not Attended' ? 'selected' : '' ?>>Counselor: Not Attended</option>
                </select>

                <select name="sort" class="select-inline">
                    <option value="created_desc" <?= $sort === 'created_desc' ? 'selected' : '' ?>>Newest</option>
                    <option value="date_asc" <?= $sort === 'date_asc' ? 'selected' : '' ?>>Oldest</option>
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name A–Z</option>
                    <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Name Z–A</option>
                </select>

                <button type="submit">Apply</button>
            </form>

            <form method="get" action="export_excel.php" style="margin-left:auto;">
                <input type="hidden" name="search" value="<?= htmlspecialchars($search ?? '') ?>">
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter ?? '') ?>">
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort ?? '') ?>">
                <button type="submit" class="small-btn">Export CSV</button>
            </form>
        </div>

        <div class="card">
            <?php if (!$students || $students->num_rows === 0): ?>
                <div class="small">No students found.</div>
            <?php else: ?>
                <table class="table" role="table" aria-label="Students table">
                    <thead>
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th>Student Name</th>
                            <th>Username / Email</th>
                            <th style="width:110px;">Last Exam</th>
                            <th style="width:90px;">Last Score</th>
                            <th style="width:160px;">Counselor</th>
                            <th style="width:170px;">Date</th>
                            <th style="width:110px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($s = $students->fetch_assoc()): 
                            // safe fields (avoid passing null into htmlspecialchars)
                            $id = intval($s['id']);
                            $name = htmlspecialchars($s['full_name'] ?? '');
                            $username = htmlspecialchars($s['username'] ?? '');
                            $email = htmlspecialchars($s['email'] ?? '');
                            $last_exam = htmlspecialchars($s['last_exam'] ?? '-');
                            $last_score = htmlspecialchars((string)($s['last_score'] ?? '-'));
                            $counselor = htmlspecialchars($s['counselor_sent'] ?? 'Pending');
                            $created = htmlspecialchars($s['created'] ?? '');
                        ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= $name ?></td>
                            <td>
                                <strong><?= $username ?></strong><br>
                                <small class="small"><?= $email ?></small>
                            </td>
                            <td><?= $last_exam ?></td>
                            <td><?= $last_score ?></td>
                            <td>
                                <form method="post" style="margin:0;">
                                    <input type="hidden" name="update_status" value="1">
                                    <input type="hidden" name="student_id" value="<?= $id ?>">
                                    <select name="counselor_sent" class="select-inline" onchange="this.form.submit()">
                                        <option value="Pending" <?= $counselor === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Attended" <?= $counselor === 'Attended' ? 'selected' : '' ?>>Attended</option>
                                        <option value="Not Attended" <?= $counselor === 'Not Attended' ? 'selected' : '' ?>>Not Attended</option>
                                    </select>
                                </form>
                            </td>
                            <td><?= $created ?></td>
                            <td>
                                <a class="small-btn secondary" href="student_report.php?id=<?= $id ?>" target="_blank">Print</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</div>

</body>
</html>
