<?php
require_once "../../app/db.php";
include "inc/header.php";

// ADD NEW EQ QUESTION
if (isset($_POST['add'])) {
    $q = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct_option'];

    $stmt = $mysqli->prepare("
        INSERT INTO eq_questions (question, option_a, option_b, option_c, option_d, correct_option)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssss", $q, $a, $b, $c, $d, $correct);
    $stmt->execute();
}

// DELETE QUESTION
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $mysqli->query("DELETE FROM eq_questions WHERE id=$id");
    header("Location: eq_questions.php");
    exit;
}

$questions = $mysqli->query("SELECT * FROM eq_questions ORDER BY id DESC");
?>

<h2>Manage EQ Questions</h2>

<!-- ADD FORM -->
<form method="POST" style="background:white; padding:20px; border-radius:10px;">
    <h3>Add New EQ Question</h3>

    <label>Question</label>
    <textarea name="question" required style="width:100%; height:70px;"></textarea>

    <label>Option A</label>
    <input type="text" name="option_a" required>

    <label>Option B</label>
    <input type="text" name="option_b" required>

    <label>Option C</label>
    <input type="text" name="option_c" required>

    <label>Option D</label>
    <input type="text" name="option_d" required>

    <label>Correct Option (A/B/C/D)</label>
    <input type="text" name="correct_option" maxlength="1" required>

    <br><br>
    <button name="add" style="padding:10px; background:black; color:white;">Add Question</button>
</form>

<br>

<!-- LIST QUESTIONS -->
<table border="1" cellpadding="10" style="width:100%; background:white;">
<tr>
    <th>ID</th>
    <th>Question</th>
    <th>A</th>
    <th>B</th>
    <th>C</th>
    <th>D</th>
    <th>Correct</th>
    <th>Action</th>
</tr>

<?php while ($q = $questions->fetch_assoc()): ?>
<tr>
    <td><?= $q['id'] ?></td>
    <td><?= $q['question'] ?></td>
    <td><?= $q['option_a'] ?></td>
    <td><?= $q['option_b'] ?></td>
    <td><?= $q['option_c'] ?></td>
    <td><?= $q['option_d'] ?></td>
    <td><strong><?= $q['correct_option'] ?></strong></td>
    <td>
        <a href="eq_questions.php?delete=<?= $q['id'] ?>" style="color:red;">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<?php include "inc/footer.php"; ?>
