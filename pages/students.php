<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/Student.php";
    include_once "../includes/parts/header.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new Student();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: students.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new Student();
        $item->student_id = $_POST['student_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: students.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new Student();
        $item->student_id = $_POST['student_id'];
        $item->Delete();
        header("Location: students.php");
        exit();
    }

    $items = Student::Get();
?>
    <main>
        <h1 style="margin-bottom: 20px;">Студенты</h1>

        <h2 style="margin-bottom: 20px;">Добавить студента</h2>
        <form method="post">
            <input type="text" name="last_name" placeholder="Фамилия"><br>
            <input type="text" name="first_name" placeholder="Имя"><br>
            <input type="text" name="middle_name" placeholder="Отчество"><br>
            <input type="number" name="group_id" placeholder="ID группы"><br>
            <input type="date" name="dismissal_date"><br>
            <button type="submit" name="create">Добавить</button>
        </form>

        <h2 style="margin-top: 20px;">Существующие студенты</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
                <th>Группа ID</th>
                <th>Дата отчисления</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item->student_id ?></td>
                    <td><?= $item->last_name ?></td>
                    <td><?= $item->first_name ?></td>
                    <td><?= $item->middle_name ?? '' ?></td>
                    <td><?= $item->group_id ?? '' ?></td>
                    <td><?= $item->dismissal_date ?? '' ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?= $item->student_id ?>">
                            <input type="text" name="last_name" value="<?= $item->last_name ?>">
                            <input type="text" name="first_name" value="<?= $item->first_name ?>">
                            <input type="text" name="middle_name" value="<?= $item->middle_name ?? '' ?>">
                            <input type="number" name="group_id" value="<?= $item->group_id ?? '' ?>">
                            <input type="date" name="dismissal_date" value="<?= $item->dismissal_date ?? '' ?>">
                            <button type="submit" name="update">Обновить</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="student_id" value="<?= $item->student_id ?>">
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
