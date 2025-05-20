<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1>Дисциплины</h1>

    <h2 style="margin-top: 15px;">Добавить дисциплину</h2>
    <form id="add-discipline-form" class="add_form">
        <input type="text" name="discipline_name" placeholder="Название дисциплины"><br>
        <button type="submit" class="add-students-btn">Добавить</button>
    </form>

    <h2 style="margin-top: 15px;">Список дисциплин</h2>
    <table id="disciplines-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название дисциплины</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<script src="../js/jquery-3.7.1.min.js"></script>
<script src="../js/app.js"></script>

<?php include_once "../includes/parts/footer.php"; ?>