<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/Discipline.php";
    include_once "../includes/parts/header.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new Discipline();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: disciplines.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new Discipline();
        $item->discipline_id = $_POST['discipline_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: disciplines.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new Discipline();
        $item->discipline_id = $_POST['discipline_id'];
        $item->Delete();
        header("Location: disciplines.php");
        exit();
    }

    $items = Discipline::Get();
?>

<main>
    <h1 style="margin-bottom: 20px;">Дисциплины</h1>

    <h2 style="margin-bottom: 20px;">Добавить дисциплину</h2>
    <form class="add_form" method="post">
        <input type="text" name="discipline_name" placeholder="Название дисциплины"><br>
        <button class="add-students-btn" type="submit" name="create">Добавить</button>
    </form>

    <h2 style="margin-top: 20px;">Существующие дисциплины</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Название дисциплины</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->discipline_id ?></td>
                <td><?= $item->discipline_name ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="discipline_id" value="<?= $item->discipline_id ?>">
                        <input type="text" name="discipline_name" value="<?= $item->discipline_name ?>">
                        <button class="edit-students-btn" type="submit" name="update">Обновить</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="discipline_id" value="<?= $item->discipline_id ?>">
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