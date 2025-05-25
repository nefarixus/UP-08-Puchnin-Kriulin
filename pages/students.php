<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1>Студенты</h1>

    <!-- Форма добавления -->
    <h2 style="margin-top: 15px;">Добавить студента</h2>
    <form id="add-student-form" class="add_form">
        <input type="text" name="last_name" placeholder="Фамилия" required><br>
        <input type="text" name="first_name" placeholder="Имя" required><br>
        <input type="text" name="middle_name" placeholder="Отчество" required><br>
        <input type="number" name="group_id" placeholder="ID группы"><br>
        <input type="date" name="dismissal_date"><br>
        <button type="submit" class="add-students-btn">Добавить</button>
    </form>

    <!-- Таблица студентов -->
    <h2 style="margin-top: 15px;">Список студентов</h2>
    <table id="students-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
                <th>Группа</th>
                <th>Дата отчисления</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<?php include_once "../includes/parts/footer.php"; ?>