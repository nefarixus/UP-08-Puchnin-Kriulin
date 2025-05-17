<?php
    require_once "../includes/parts/connection.php";
    include_once "../includes/parts/header.php";

    $query = mysqli_query($db_connection, "SELECT * FROM Teachers");

    if (isset($_POST['DELETE_RECORD_TEACHERS']) && isset($_POST['teacher_id'])) {
        $id_teacher = $_POST['teacher_id'];
        $query = "DELETE FROM Teachers WHERE teacher_id = '$id_teacher'";
        $resultDelete = mysqli_query($db_connection, $query) or die ("Ошибка в запросе: " . mysqli_error($db_connection));
        header("Location: /EP-08/pages/teachers.php");
        exit();
    }
?>
    <main>
        <h1>Преподаватели</h1>

        <table>
            <thead>
                <tr>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Логин</th>
                    <th>Пароль</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['first_name']) ?></td>
                        <td><?= $row['middle_name'] ? htmlspecialchars($row['middle_name']) : '----' ?></td>
                        <td><?= $row['login'] ?></td>
                        <td><?= $row['password'] ?></td>
                        <td>
                            <a href="edit_teacher.php?id=<?= $row['teacher_id'] ?>" class="edit-students-btn">Редактировать</a>
                            <form method="POST" action="students.php" style="display: inline;">
                                <input type="hidden" name="teacher_id" value="<?= $row['teacher_id'] ?>">
                                <button type="submit" name="DELETE_RECORD_TEACHERS" class="delete-students-btn">Удаление</button>
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