<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1>Учебные группы</h1>

    <h2 style="margin-top: 15px;">Добавить группу</h2>
    <form id="add-group-form" class="add_form">
        <input type="text" name="group_name" placeholder="Название группы" required><br>
        <button type="submit" class="add-groups-btn">Добавить</button>
    </form>

    <!-- Поиск -->
    
    <h2 style="margin-top: 15px;">Поиск по группам</h2>
    <input class="search" type="text" id="group-search-input" placeholder="Введите название группы">
    <button class="reset-group-search" id="reset-group-filters">Сбросить</button>

    <h2 style="margin-top: 15px;">Список групп</h2>
    <table id="groups-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название группы</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <h2 style="margin-top: 15px;">Выберите группу для просмотра студентов</h2>
    <select class="group-select-for-students" id="group-select-for-students" class="form-control">
        <option value="">Выберите группу</option>
    </select>

    <h2 style="margin-top: 15px;">Студенты группы</h2>
    <table id="group-students-table" class="table">
        <thead>
            <tr>
                <th>ID студента</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<?php include_once "../includes/parts/footer.php"; ?>