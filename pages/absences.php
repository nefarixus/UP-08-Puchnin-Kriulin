<?php include "../includes/parts/header.php"; ?>
    <main>
        <h1>Пропуски студентов</h1>

        <!-- Форма добавления -->
        <h2>Добавить пропуск</h2>
        <form id="add-absence-form">
            <input type="number" name="lesson_id" placeholder="ID занятия"><br>
            <input type="number" name="student_id" placeholder="ID студента"><br>
            <input type="number" name="minutes_missed" placeholder="Количество минут"><br>
            <input type="file" id="explanatory-note-file" accept="application/pdf"><br>
            <button type="submit" class="add-absence-btn">Добавить пропуск</button>
        </form>

        <!-- Таблица пропусков -->
        <table id="absences-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID занятия</th>
                    <th>ID студента</th>
                    <th>Фамилия студента</th>
                    <th>Группа</th>
                    <th>Минут пропущено</th>
                    <th>Объяснительная</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Поиск -->
        <h2>Поиск по пропуску</h2>
        <div class="search-bar">
            <input type="text" id="absence-search-input" placeholder="Поиск по ФИО или группе">
            <button class="reset-absence-filters" id="reset-absence-filters">Сбросить</button>
        </div>
    </main>
<?php include "../includes/parts/footer.php"; ?>