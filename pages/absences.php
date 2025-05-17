<?php
    require_once "../includes/parts/connection.php";
    require_once "../models/Absence.php";
    include_once "../includes/parts/header.php";

    // --- CREATE ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        $item = new Absence();
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Insert();
        header("Location: absences.php");
        exit();
    }

    // --- UPDATE ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $item = new Absence();
        $item->absence_id = $_POST['absence_id'];
        foreach ($_POST as $key => $value) {
            if (property_exists($item, $key)) {
                $item->{$key} = $value;
            }
        }
        $item->Update();
        header("Location: absences.php");
        exit();
    }

    // --- DELETE ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $item = new Absence();
        $item->absence_id = $_POST['absence_id'];
        $item->Delete();
        header("Location: absences.php");
        exit();
    }

    // --- READ ---
    $items = Absence::Get();
?>
    <main>
        <h1 style="margin-bottom: 20px;">Пропуски занятий</h1>

        <h2 style="margin-bottom: 20px;">Добавить пропуск занятия</h2>
        <form method="post">
            <input type="number" name="lesson_id" placeholder="ID занятия"><br>
            <input type="number" name="student_id" placeholder="ID студента"><br>
            <input type="number" name="minutes_missed" placeholder="Минут пропущено"><br>
            <input type="text" name="explanatory_note_path" placeholder="Пояснение (путь к файлу)"><br>
            <button type="submit" name="create">Добавить</button>
        </form>

        <h2 style="margin-top: 20px;">Существующие пропуски занятий</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>ID занятия</th>
                <th>ID студента</th>
                <th>Минут пропущено</th>
                <th>Пояснение</th>
                <th>Действия</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item->absence_id ?></td>
                    <td><?= $item->lesson_id ?></td>
                    <td><?= $item->student_id ?></td>
                    <td><?= $item->minutes_missed ?></td>
                    <td><?= $item->explanatory_note_path ?? '' ?></td>
                    <td>
                        <!-- Форма обновления -->
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="absence_id" value="<?= $item->absence_id ?>">
                            <input type="number" name="lesson_id" value="<?= $item->lesson_id ?>">
                            <input type="number" name="student_id" value="<?= $item->student_id ?>">
                            <input type="number" name="minutes_missed" value="<?= $item->minutes_missed ?>">
                            <input type="text" name="explanatory_note_path" value="<?= $item->explanatory_note_path ?? '' ?>">
                            <button type="submit" name="update">Обновить</button>
                        </form>

                        <!-- Форма удаления -->
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="absence_id" value="<?= $item->absence_id ?>">
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
