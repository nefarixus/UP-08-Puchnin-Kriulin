<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/Lesson.php";
    include_once "../includes/parts/header.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new Lesson();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: lessons.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new Lesson();
        $item->lesson_id = $_POST['lesson_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: lessons.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new Lesson();
        $item->lesson_id = $_POST['lesson_id'];
        $item->Delete();
        header("Location: lessons.php");
        exit();
    }

    $items = Lesson::Get();
?>

<main>
    <h1 style="margin-bottom: 20px;">Занятия</h1>
    <h2 style="margin-bottom: 20px;">Добавить занятиe</h2>


    <form class="add_form" method="post">
        <input type="number" name="program_id" placeholder="ID программы"><br>
        <input type="number" name="group_id" placeholder="ID группы"><br>
        <input type="number" name="teacher_id" placeholder="ID преподавателя"><br>
        <input type="datetime-local" name="lesson_date"><br>
        <input type="number" name="duration_minutes" placeholder="Длительность (минуты)"><br>
        <button class="add-students-btn" type="submit" name="create">Добавить</button>
    </form>

    <h2 style="margin-top: 20px;">Существующие занятия</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>ID программы</th>
            <th>ID группы</th>
            <th>ID преподавателя</th>
            <th>Дата и время</th>
            <th>Длительность</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->lesson_id ?></td>
                <td><?= $item->program_id ?></td>
                <td><?= $item->group_id ?></td>
                <td><?= $item->teacher_id ?></td>
                <td><?= $item->lesson_date ?></td>
                <td><?= $item->duration_minutes ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="lesson_id" value="<?= $item->lesson_id ?>">
                        <input type="number" name="program_id" value="<?= $item->program_id ?>">
                        <input type="number" name="group_id" value="<?= $item->group_id ?>">
                        <input type="number" name="teacher_id" value="<?= $item->teacher_id ?>">
                        <input type="datetime-local" name="lesson_date" value="<?= substr($item->lesson_date, 0, 16) ?>">
                        <input type="number" name="duration_minutes" value="<?= $item->duration_minutes ?>">
                        <button class="edit-students-btn" type="submit" name="update">Обновить</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="lesson_id" value="<?= $item->lesson_id ?>">
                        <button class="delete-students-btn" type="submit" name="delete">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
