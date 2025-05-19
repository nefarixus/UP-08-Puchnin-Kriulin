<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/StudentGroup.php";
    include_once "../includes/parts/header.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new StudentGroup();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: student_groups.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new StudentGroup();
        $item->group_id = $_POST['group_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: student_groups.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new StudentGroup();
        $item->group_id = $_POST['group_id'];
        $item->Delete();
        header("Location: student_groups.php");
        exit();
    }

    $items = StudentGroup::Get();
?>

<main>
    <h1 style="margin-bottom: 20px;">Учебные группы</h1>

    <h2 style="margin-bottom: 20px;">Добавить учебную группу</h2>
    <form class="add_form" method="post">
        <input type="text" name="group_name" placeholder="Название группы"><br>
        <button class="add-students-btn" type="submit" name="create">Добавить</button>
    </form>

    <h2 style="margin-top: 20px;">Существующие учебные группы</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Название группы</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->group_id ?></td>
                <td><?= $item->group_name ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="group_id" value="<?= $item->group_id ?>">
                        <input type="text" name="group_name" value="<?= $item->group_name ?>">
                        <button class="edit-students-btn" type="submit" name="update">Обновить</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="group_id" value="<?= $item->group_id ?>">
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