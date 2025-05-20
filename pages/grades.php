<?php
    include_once "../includes/parts/header.php";
?>

<main>
    <h1 style="margin-bottom: 20px;">Оценки</h1>

    <form id="add-grade-form" class="add_form" method="post">
        <input type="number" name="lesson_id" placeholder="ID занятия"><br>
        <input type="number" name="student_id" placeholder="ID студента"><br>
        <input type="number" name="grade_value" placeholder="Оценка"><br>
        <button class="add-students-btn" type="submit" name="create">Добавить</button>
    </form>

    <h2 style="margin-top: 15px;">Список оценок</h2>
    <table id="grades-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID занятия</th>
                <th>ID студента</th>
                <th>Оценка</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<?php include_once "../includes/parts/footer.php"; ?>