<?php
    require_once "../includes/parts/connection.php";
    include_once "../includes/parts/header.php";

    $query = mysqli_query($db_connection, "SELECT * FROM Consultations");

    if (isset($_POST['DELETE_RECORD_CONSULTATION']) && isset($_POST['consultation_id'])) {
        $id_consultation = $_POST['consultation_id'];
        $query = "DELETE FROM Consultations WHERE consultation_id = '$id_consultation'";
        $resultDelete = mysqli_query($db_connection, $query) or die ("Ошибка в запросе: " . mysqli_error($db_connection));
        header("Location: /EP-08/pages/consultations.php");
        exit();
    }
?>
    <main>
        <h1>Консультации</h1>

        <table>
            <thead>
                <tr>
                    <th>Номер преподавателя</th>
                    <th>Номер группы</th>
                    <th>Номер студенты</th>
                    <th>Дата консультации</th>
                    <th>Показано</th>
                    <th>Сдано</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $row['teacher_id'] ?></td>
                        <td><?= $row['group_id'] ?></td>
                        <td><?= $row['student_id'] ?></td>
                        <td>
                            <?php if ($row['consultation_date']) : ?>
                                <?= date('d.m.Y', strtotime($row['consultation_date'])) ?>
                            <?php else : ?>
                                ----
                            <?php endif; ?>
                        </td>
                        <td><?= $row['is_present'] ?></td>
                        <td><?= $row['is_completed'] ?></td>
                        <td>
                            <form method="POST" action="consultations.php" style="display: inline;">
                                <input type="hidden" name="consultation_id" value="<?= $row['consultation_id'] ?>">
                                <button type="submit" name="DELETE_RECORD_CONSULTATION" class="delete-students-btn">Удаление</button>
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
