<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1>Программы дисциплин</h1>

    <h2 style="margin-top: 15px;">Добавить программу</h2>
    <form id="add-program-form" class="add_form">
        <input type="number" name="discipline_id" placeholder="ID дисциплины"><br>
        <input type="text" name="topic" placeholder="Тема"><br>
        <input type="text" name="lesson_type" placeholder="Тип занятия"><br>
        <input type="number" name="hours" placeholder="Часов"><br>
        <button type="submit" class="add-program-btn">Добавить</button>
    </form>

    <h2 style="margin-top: 15px;">Список программ</h2>
    <table id="programs-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID дисциплины</th>
                <th>Тема</th>
                <th>Тип занятия</th>
                <th>Часов</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<script src="../js/jquery-3.7.1.min.js"></script>
<script src="../js/app.js"></script>

<?php include_once "../includes/parts/footer.php"; ?>