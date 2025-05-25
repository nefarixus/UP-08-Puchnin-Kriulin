<?php include_once "../includes/parts/header.php"; ?>

    <main>
        <h1>Дисциплины</h1>

        <h2 style="margin-top: 15px;">Добавить дисциплину</h2>
        <form id="add-discipline-form" class="add_form">
            <input type="text" name="discipline_name" placeholder="Название дисциплины" required><br>
            <button type="submit" class="add-disciplines-btn">Добавить</button>
        </form>

        <!-- Поиск -->
        <h2 style="margin-top: 15px;">Поиск по дисциплинам</h2>
        <input type="text" class="discipline-search-input" id="discipline-search-input" placeholder="Введите название дисциплины">
        <button class="reset-discipline-filters" id="reset-discipline-filters">Сбросить</button>

        <!-- Список дисциплин -->
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

        <!-- Выбор дисциплины -->
        <h2 style="margin-top: 15px;">Выберите дисциплину, чтобы посмотреть нагрузку</h2>
        <select class="discipline-group-select" id="discipline-group-select" class="form-control">
            <option value="">Выберите дисциплину</option>
        </select>

        <h2 style="margin-top: 15px;">Информация по группам</h2>
        <table id="discipline-students-table" class="table">
            <thead>
                <tr>
                    <th>Группа</th>
                    <th>Количество занятий</th>
                    <th>Часов всего</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </main>

<?php include_once "../includes/parts/footer.php"; ?>