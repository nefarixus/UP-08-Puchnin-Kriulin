<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/DisciplineProgram.php";
    include_once "../includes/parts/header.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new DisciplineProgram();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: discipline_programs.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new DisciplineProgram();
        $item->program_id = $_POST['program_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: discipline_programs.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new DisciplineProgram();
        $item->program_id = $_POST['program_id'];
        $item->Delete();
        header("Location: discipline_programs.php");
        exit();
    }

    $items = DisciplineProgram::Get();
?>

<main>
    <h1 style="margin-bottom: 20px;">Программа дисциплины</h1>

    <h2 style="margin-bottom: 20px;">Добавить программу дисциплины</h2>
    <form class="add_form" method="post">
        <input type="number" name="discipline_id" placeholder="ID дисциплины"><br>
        <input type="text" name="topic" placeholder="Тема"><br>
        <input type="text" name="lesson_type" placeholder="Тип занятия"><br>
        <input type="number" name="hours" placeholder="Часов"><br>
        <button class="add-students-btn" type="submit" name="create">Добавить</button>
    </form>

    <h2 style="margin-top: 20px;">Существующие программы дисциплины</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>ID дисциплины</th>
            <th>Тема</th>
            <th>Тип занятия</th>
            <th>Часов</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->program_id ?></td>
                <td><?= $item->discipline_id ?></td>
                <td><?= $item->topic ?></td>
                <td><?= $item->lesson_type ?></td>
                <td><?= $item->hours ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="program_id" value="<?= $item->program_id ?>">
                        <input type="number" name="discipline_id" value="<?= $item->discipline_id ?>">
                        <input type="text" name="topic" value="<?= $item->topic ?>">
                        <input type="text" name="lesson_type" value="<?= $item->lesson_type ?>">
                        <input type="number" name="hours" value="<?= $item->hours ?>">
                        <button class="edit-students-btn" type="submit" name="update">Обновить</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="program_id" value="<?= $item->program_id ?>">
                        <button class="delete-students-btn" type="submit" name="delete">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
<?php
    include_once "../includes/parts/footer.php";
?>