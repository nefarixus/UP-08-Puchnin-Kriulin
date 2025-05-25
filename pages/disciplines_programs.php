<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1>Программы дисциплин</h1>

    <h2 style="margin-top: 15px;">Добавить программу</h2>
    <form id="add-program-form" class="add_form">
        <select class="discipline-option-select" name="discipline_id" id="program-discipline-select" required></select><br>
        <input type="text" name="topic" placeholder="Тема"><br>
        <select class="discipline-option-select" name="lesson_type" id="program-lesson-type">
            <option  value="">Выберите тип занятия</option>
            <option value="лекция">Лекция</option>
            <option value="практика">Практика</option>
            <option value="консультация">Консультация</option>
            <option value="курсовая работа">Курсовая работа</option>
            <option value="экзамен">Экзамен</option>
        </select><br>
        <input type="number" name="hours" placeholder="Часов"><br>
        <button type="submit" class="add-programs-btn">Добавить</button>
    </form>

    <h2 style="margin-top: 15px;">Список программ</h2>
    <table id="programs-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Дисциплина</th>
                <th>Тема</th>
                <th>Тип занятия</th>
                <th>Часов</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<?php include_once "../includes/parts/footer.php"; ?>