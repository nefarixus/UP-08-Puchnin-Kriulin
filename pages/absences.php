<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1>Пропуски занятий</h1>

    <h2 style="margin-top: 15px;">Добавить пропуск</h2>
    <form id="add-absence-form" class="add_form">
        <input type="number" name="lesson_id" placeholder="ID занятия"><br>
        <input type="number" name="student_id" placeholder="ID студента"><br>
        <input type="number" name="minutes_missed" placeholder="Минут пропущено"><br>
        <input type="text" name="explanatory_note_path" placeholder="Пояснение (путь)"><br>
        <button type="submit" class="add-absences-btn">Добавить</button>
    </form>

    <h2 style="margin-top: 15px;">Список пропусков</h2>
    <table id="absences-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID занятия</th>
                <th>ID студента</th>
                <th>Минут пропущено</th>
                <th>Пояснение</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<?php include_once "../includes/parts/footer.php"; ?>