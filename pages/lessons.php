<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1 style="margin-bottom: 20px;">Занятия</h1>

    <form id="add-lesson-form" class="add_form">
        <input type="number" name="program_id" placeholder="ID программы"><br>
        <input type="number" name="group_id" placeholder="ID группы"><br>
        <input type="number" name="teacher_id" placeholder="ID преподавателя"><br>
        <input type="datetime-local" name="lesson_date"><br>
        <input type="number" name="duration_minutes" placeholder="Длительность (минуты)"><br>
        <button class="add-students-btn" type="submit">Добавить</button>
    </form>

    <h2 style="margin-top:15px;">Список занятий</h2>
    <table id="lessons-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID программы</th>
                <th>ID группы</th>
                <th>ID преподавателя</th>
                <th>Дата и время</th>
                <th>Длительность</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<?php include_once "../includes/parts/footer.php"; ?>