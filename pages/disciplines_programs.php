<?php
require_once "../includes/parts/connection.php";
include_once "../includes/parts/header.php";


$query = mysqli_query($db_connection, "SELECT * FROM Discipline_Programs");
?>

<main>
    <h1>Программа дисциплины</h1>

    <table>
        <thead>
            <tr>
                <th>Тема занятия</th>
                <th>Тип занятия</th>
                <th>Количество часов на занятие</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?=htmlspecialchars($row['topic'])?></td>
                    <td><?= htmlspecialchars($row['lesson_type']) ?></td>
                    <td><?= htmlspecialchars($row['hours']) ?></td>
                    <td>
                        <a href="edit_students.php?id=<?= $row['program_id']?>" class="edit-students-btn">Редактировать</a>
                        <form method="POST" action="students.php" style="display: inline;">
                            <input type="hidden" name="student_id" value="<?= $row['program_id']?>">
                            <button type="submit" name="DELETE_RECORD_STUDENT" class="delete-students-btn">Удаление</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>
<?php
include_once "../includes/parts/footer.php";
?>
<?php
    if (isset($_POST['DELETE_RECORD_STUDENT']) && isset($_POST['student_id'])) {
        $id_account = $_POST['student_id'];
        $query = "DELETE FROM Students WHERE student_id = '$id_account'";
        $resultDelete = mysqli_query($db_connection, $query) or die ("Ошибка в запросе: " . mysqli_error($db_connection));
        header("Location: students.php");
        exit();
    }

?>