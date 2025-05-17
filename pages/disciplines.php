<?php
require_once "../includes/parts/connection.php";
include_once "../includes/parts/header.php";


$query = mysqli_query($db_connection, "SELECT * FROM Disciplines");
?>

<main>
    <h1>Дисциплины</h1>

    <table>
        <thead>
            <tr>
                <th>Наименование дисциплины</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?=htmlspecialchars($row['discipline_name'])?></td>
                    <td>
                        <a href="edit_students.php?id=<?= $row['discipline_id'] ?>" class="edit-students-btn">Редактировать</a>
                        <form method="POST" action="students.php" style="display: inline;">
                            <input type="hidden" name="student_id" value="<?= $row['discipline_id'] ?>">
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