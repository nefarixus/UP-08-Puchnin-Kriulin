<?php
    require_once "../includes/parts/connection.php";
    include_once "../includes/parts/header.php";

    $query = mysqli_query($db_connection, "SELECT * FROM Teacher_Workload");

    if (isset($_POST['DELETE_RECORD_WORKLOAD']) && isset($_POST['workload_id'])) {
        $id_workload  = $_POST['workload_id'];
        $query = "DELETE FROM Teacher_Workload WHERE workload_id = '$id_workload'";
        $resultDelete = mysqli_query($db_connection, $query) or die ("Ошибка в запросе: " . mysqli_error($db_connection));
        header("Location: /EP-08/pages/workload.php");
        exit();
    }
?>
    <main>
        <h1>Преподавательская нагрузка</h1>

        <table>
            <thead>
                <tr>
                    <th>Номер преподавателя</th>
                    <th>Номер дисциплины</th>
                    <th>Номер группы</th>
                    <th>Часы лекции</th>
                    <th>Часы практик</th>
                    <th>Часы консультаций</th>
                    <th>Часы курсового проекта</th>
                    <th>Часы экзамена</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $row['teacher_id'] ?></td>
                        <td><?= $row['discipline_id'] ?></td>
                        <td><?= $row['group_id'] ?></td>
                        <td><?= $row['lecture_hours'] ?></td>
                        <td><?= $row['practice_hours'] ?></td>
                        <td><?= $row['consultation_hours'] ?></td>
                        <td><?= $row['course_project_hours'] ?></td>
                        <td><?= $row['exam_hours'] ?></td>
                        <td>
                            <a href="edit_workload.php?id=<?= $row['workload_id'] ?>" class="edit-students-btn">Редактировать</a>
                            <form method="POST" action="workload.php" style="display: inline;">
                                <input type="hidden" name="workload_id" value="<?= $row['workload_id'] ?>">
                                <button type="submit" name="DELETE_RECORD_WORKLOAD" class="delete-students-btn">Удаление</button>
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
