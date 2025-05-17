<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/Consultation.php";
    include_once "../includes/parts/header.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new Consultation();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: consultations.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new Consultation();
        $item->consultation_id = $_POST['consultation_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: consultations.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new Consultation();
        $item->consultation_id = $_POST['consultation_id'];
        $item->Delete();
        header("Location: consultations.php");
        exit();
    }

    $items = Consultation::Get();
?>

<main>
    <h1 style="margin-bottom: 20px;">Консультации</h1>

    <h2 style="margin-bottom: 20px;">Добавить консультацию</h2>
    <form method="post">
        <input type="number" name="teacher_id" placeholder="ID преподавателя"><br>
        <input type="number" name="group_id" placeholder="ID группы"><br>
        <input type="number" name="student_id" placeholder="ID студента (опционально)"><br>
        <input type="date" name="consultation_date" placeholder="Дата консультации"><br>
        <input type="number" name="is_present" placeholder="Присутствовал (0 или 1)"><br>
        <input type="number" name="is_completed" placeholder="Завершено (0 или 1)"><br>
        <button type="submit" name="create">Добавить</button>
    </form>

    <h2 style="margin-top:20px;">Существующие консультации</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>ID преподавателя</th>
            <th>ID группы</th>
            <th>ID студента</th>
            <th>Дата</th>
            <th>Присутствует</th>
            <th>Завершена</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->consultation_id ?></td>
                <td><?= $item->teacher_id ?></td>
                <td><?= $item->group_id ?></td>
                <td><?= $item->student_id ?? '' ?></td>
                <td><?= $item->consultation_date ?></td>
                <td><?= $item->is_present ?></td>
                <td><?= $item->is_completed ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="consultation_id" value="<?= $item->consultation_id ?>">
                        <input type="number" name="teacher_id" value="<?= $item->teacher_id ?>">
                        <input type="number" name="group_id" value="<?= $item->group_id ?>">
                        <input type="number" name="student_id" value="<?= $item->student_id ?? '' ?>">
                        <input type="date" name="consultation_date" value="<?= $item->consultation_date ?>">
                        <input type="number" name="is_present" value="<?= $item->is_present ?>">
                        <input type="number" name="is_completed" value="<?= $item->is_completed ?>">
                        <button type="submit" name="update">Обновить</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="consultation_id" value="<?= $item->consultation_id ?>">
                        <button type="submit" name="delete">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
