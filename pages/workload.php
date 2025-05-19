<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/Workload.php";
    include_once "../includes/parts/header.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new Workload();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: workload.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new Workload();
        $item->workload_id = $_POST['workload_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: workload.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new Workload();
        $item->workload_id = $_POST['workload_id'];
        $item->Delete();
        header("Location: workload.php");
        exit();
    }

    $items = Workload::Get();
?>

<main>
    <h1 style="margin-bottom: 20px;">Нагрузка преподавателей</h1>

    <h2 style="margin-bottom: 20px;">Добавить преподавательскую нагрузку</h2>
    <form class="add_form" method="post">
        <input type="number" name="teacher_id" placeholder="ID преподавателя"><br>
        <input type="number" name="discipline_id" placeholder="ID дисциплины"><br>
        <input type="number" name="group_id" placeholder="ID группы"><br>
        <input type="number" name="lecture_hours" placeholder="Лекции (часов)"><br>
        <input type="number" name="practice_hours" placeholder="Практика (часов)"><br>
        <input type="number" name="consultation_hours" placeholder="Консультации (часов)"><br>
        <input type="number" name="course_project_hours" placeholder="Курсовой проект (часов)"><br>
        <input type="number" name="exam_hours" placeholder="Экзамены (часов)"><br>
        <button class="add-students-btn" type="submit" name="create">Добавить</button>
    </form>

    <h2 style="margin-top: 20px;">Существующие преподавательские нагрузки</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>ID преподавателя</th>
            <th>ID дисциплины</th>
            <th>ID группы</th>
            <th>Лекции</th>
            <th>Практика</th>
            <th>Консультации</th>
            <th>Курсовой проект</th>
            <th>Экзамены</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->workload_id ?></td>
                <td><?= $item->teacher_id ?></td>
                <td><?= $item->discipline_id ?></td>
                <td><?= $item->group_id ?></td>
                <td><?= $item->lecture_hours ?></td>
                <td><?= $item->practice_hours ?></td>
                <td><?= $item->consultation_hours ?></td>
                <td><?= $item->course_project_hours ?></td>
                <td><?= $item->exam_hours ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="workload_id" value="<?= $item->workload_id ?>">
                        <input type="number" name="teacher_id" value="<?= $item->teacher_id ?>">
                        <input type="number" name="discipline_id" value="<?= $item->discipline_id ?>">
                        <input type="number" name="group_id" value="<?= $item->group_id ?>">
                        <input type="number" name="lecture_hours" value="<?= $item->lecture_hours ?>">
                        <input type="number" name="practice_hours" value="<?= $item->practice_hours ?>">
                        <input type="number" name="consultation_hours" value="<?= $item->consultation_hours ?>">
                        <input type="number" name="course_project_hours" value="<?= $item->course_project_hours ?>">
                        <input type="number" name="exam_hours" value="<?= $item->exam_hours ?>">
                        <button class="edit-students-btn" type="submit" name="update">Обновить</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="workload_id" value="<?= $item->workload_id ?>">
                        <button class="delete-students-btn" type="submit" name="delete">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
