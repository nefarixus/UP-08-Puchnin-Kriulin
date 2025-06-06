<?php include_once "../includes/parts/header.php"; ?>
<main>
    <h1>Нагрузка преподавателей</h1>


<form id="add-workload-form" class="add_form">
    <input type="number" name="teacher_id" placeholder="ID преподавателя"><br>
    <input type="number" name="discipline_id" placeholder="ID дисциплины"><br>
    <input type="number" name="group_id" placeholder="ID группы"><br>
    <input type="number" name="lecture_hours" placeholder="Лекции (часов)"><br>
    <input type="number" name="practice_hours" placeholder="Практика (часов)"><br>
    <input type="number" name="consultation_hours" placeholder="Консультации (часов)"><br>
    <input type="number" name="course_project_hours" placeholder="Курсовой проект (часов)"><br>
    <input type="number" name="exam_hours" placeholder="Экзамены (часов)"><br>
    <button class="add-students-btn" type="submit">Добавить</button>
</form>


<div class="search-sort">
    <select class="sort-group-select" id="sort-group-select">
        <option value="">Все группы</option>
    </select>
</div>


<table id="workload-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Преподаватель</th>
            <th>Дисциплина</th>
            <th>Группа</th>
            <th>Лекции</th>
            <th>Практика</th>
            <th>Консультации</th>
            <th>Курсовой проект</th>
            <th>Экзамены</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
</main>
<?php include_once "../includes/parts/footer.php"; ?>