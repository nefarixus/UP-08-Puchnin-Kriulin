<?php
    require_once "../includes/parts/connection.php";
    include_once "../includes/parts/header.php";

    $query = mysqli_query($db_connection, "SELECT * FROM Absences");

    if (isset($_POST['DELETE_RECORD_ABSENCES']) && isset($_POST['absence_id'])) {
        $id_absence  = $_POST['absence_id'];
        $query = "DELETE FROM Absences WHERE id_absence = '$id_absence'";
        $resultDelete = mysqli_query($db_connection, $query) or die ("Ошибка в запросе: " . mysqli_error($db_connection));
        header("Location: /EP-08/pages/absences.php");
        exit();
    }
?>
    <main>
        <h1>Пропуски занятий</h1>

        <table>
            <thead>
                <tr>
                    <th>Номер занятия</th>
                    <th>Номер студента</th>
                    <th>Опоздание (мин)</th>
                    <th>Объяснительная (файл)</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $row['lesson_id'] ?></td>
                        <td><?= $row['student_id'] ?></td>
                        <td><?= $row['minutes_missed'] ?></td>
                        <td><?= $row['explanatory_note_path'] ?? "Отсутствует" ?></td>
                        <td>
                            <a href="edit_absences.php?id=<?= $row['absence_id'] ?>" class="edit-students-btn">Редактировать</a>
                            <form method="POST" action="absences.php" style="display: inline;">
                                <input type="hidden" name="absence_id" value="<?= $row['absence_id'] ?>">
                                <button type="submit" name="DELETE_RECORD_ABSENCES" class="delete-students-btn">Удаление</button>
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
