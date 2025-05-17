<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/Teacher.php";
    include_once "../includes/parts/header.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new Teacher();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: teachers.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new Teacher();
        $item->teacher_id = $_POST['teacher_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: teachers.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new Teacher();
        $item->teacher_id = $_POST['teacher_id'];
        $item->Delete();
        header("Location: teachers.php");
        exit();
    }

    $items = Teacher::Get();
?>
    <main>
        <h1 style="margin-bottom: 20px;">Преподаватели</h1>

        <h2 style="margin-bottom: 20px;">Добавить преподавателя</h2>
        <form method="post">
            <input type="text" name="last_name" placeholder="Фамилия"><br>
            <input type="text" name="first_name" placeholder="Имя"><br>
            <input type="text" name="middle_name" placeholder="Отчество"><br>
            <input type="text" name="login" placeholder="Логин"><br>
            <input type="text" name="password" placeholder="Пароль"><br>
            <button type="submit" name="create">Добавить</button>
        </form>

        <h2 style="margin-top: 20px;">Существующие преподаватели</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
                <th>Логин</th>
                <th>Пароль</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item->teacher_id ?></td>
                    <td><?= $item->last_name ?></td>
                    <td><?= $item->first_name ?></td>
                    <td><?= $item->middle_name ?? '' ?></td>
                    <td><?= $item->login ?></td>
                    <td><?= $item->password ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="teacher_id" value="<?= $item->teacher_id ?>">
                            <input type="text" name="last_name" value="<?= $item->last_name ?>">
                            <input type="text" name="first_name" value="<?= $item->first_name ?>">
                            <input type="text" name="middle_name" value="<?= $item->middle_name ?? '' ?>">
                            <input type="text" name="login" value="<?= $item->login ?>">
                            <input type="text" name="password" value="<?= $item->password ?>">
                            <button type="submit" name="update">Обновить</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="teacher_id" value="<?= $item->teacher_id ?>">
                            <button type="submit" name="delete">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
<?php
    include_once "../includes/parts/footer.php";
?>