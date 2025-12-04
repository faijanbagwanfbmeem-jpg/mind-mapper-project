<?php
require_once "../../app/db.php";

// copy same query-building logic as students.php (simplified)
$search = trim($_GET['search'] ?? '');
$filter = $_GET['filter'] ?? '';
$sort = $_GET['sort'] ?? 'created_desc';

$sql = "SELECT si.*, u.username, u.email,
    (SELECT score FROM exam_results WHERE student_id = si.user_id ORDER BY id DESC LIMIT 1) AS last_score,
    (SELECT exam_type FROM exam_results WHERE student_id = si.user_id ORDER BY id DESC LIMIT 1) AS last_exam
    FROM student_info si
    LEFT JOIN users u ON u.id = si.user_id
";
$where = [];
$params = [];
$types = '';

if ($search !== '') {
    $where[] = "(si.id = ? OR si.full_name LIKE CONCAT('%',?,'%') OR u.username LIKE CONCAT('%',?,'%') OR u.email LIKE CONCAT('%',?,'%'))";
    $params[] = $search; $params[] = $search; $params[] = $search; $params[] = $search;
    $types .= 'isss';
}

if (in_array($filter, ['Pending','Attended','Not Attended'])) {
    $where[] = "si.counselor_sent = ?";
    $params[] = $filter;
    $types .= 's';
}

if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);

switch ($sort) {
    case 'iq_asc': $sql .= " ORDER BY si.iq_percent ASC"; break;
    case 'iq_desc': $sql .= " ORDER BY si.iq_percent DESC"; break;
    case 'eq_asc': $sql .= " ORDER BY si.eq_percent ASC"; break;
    case 'eq_desc': $sql .= " ORDER BY si.eq_percent DESC"; break;
    case 'name_asc': $sql .= " ORDER BY si.full_name ASC"; break;
    case 'name_desc': $sql .= " ORDER BY si.full_name DESC"; break;
    case 'date_asc': $sql .= " ORDER BY si.created ASC"; break;
    default: $sql .= " ORDER BY si.created DESC"; break;
}

if ($params) {
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = $mysqli->query($sql);
}

// send CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students_export_'.date('Ymd_His').'.csv');
$out = fopen('php://output', 'w');
// header row
fputcsv($out, ['ID','Full Name','Username','Email','Left %','Right %','IQ %','EQ %','Last Score','Last Exam','Counselor','Created']);
while ($r = $res->fetch_assoc()) {
    fputcsv($out, [
        $r['id'],
        $r['full_name'],
        $r['username'],
        $r['email'],
        $r['left_brain_percent'],
        $r['right_brain_percent'],
        $r['iq_percent'],
        $r['eq_percent'],
        $r['last_score'] ?? '',
        $r['last_exam'] ?? '',
        $r['counselor_sent'],
        $r['created']
    ]);
}
fclose($out);
exit;
