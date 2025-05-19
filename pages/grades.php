<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/Grade.php";
    include_once "../includes/parts/header.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new Grade();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: grades.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new Grade();
        $item->grade_id = $_POST['grade_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: grades.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new Grade();
        $item->grade_id = $_POST['grade_id'];
        $item->Delete();
        header("Location: grades.php");
        exit();
    }

    $items = Grade::Get();
?>

<main>
    <h1 style="margin-bottom: 20px;">Оценки</h1>

    <h2 style="margin-bottom: 20px;">Добавить оценку</h2>
    <form class="add_form" method="post">
        <input type="number" name="lesson_id" placeholder="ID занятия"><br>
        <input type="number" name="student_id" placeholder="ID студента"><br>
        <input type="number" name="grade_value" placeholder="Оценка"><br>
        <input type="text" name="color" placeholder="Цвет (например #0CAC0C)"><br>
        <button class="add-students-btn" type="submit" name="create">Добавить</button>
    </form>

    <h2 style="margin-top: 20px;">Существующие оценки</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>ID занятия</th>
            <th>ID студента</th>
            <th>Оценка</th>
            <th>Цвет</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->grade_id ?></td>
                <td><?= $item->lesson_id ?></td>
                <td><?= $item->student_id ?></td>
                <td><?= $item->grade_value ?></td>
                <td><?= $item->color ?? '' ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="grade_id" value="<?= $item->grade_id ?>">
                        <input type="number" name="lesson_id" value="<?= $item->lesson_id ?>">
                        <input type="number" name="student_id" value="<?= $item->student_id ?>">
                        <input type="number" name="grade_value" value="<?= $item->grade_value ?>">
                        <input type="text" name="color" value="<?= $item->color ?? '' ?>">
                        <button class="edit-students-btn" type="submit" name="update">Обновить</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="grade_id" value="<?= $item->grade_id ?>">
                        <button class="delete-students-btn" type="submit" name="delete">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
