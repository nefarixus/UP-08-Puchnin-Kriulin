<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1>Учебные группы</h1>

    <h2 style="margin-top: 15px;">Добавить группу</h2>
    <form id="add-group-form" class="add_form">
        <input type="text" name="group_name" placeholder="Название группы"><br>
        <button type="submit" class="add-groups-btn">Добавить</button>
    </form>

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
</main>

<?php include_once "../includes/parts/footer.php"; ?>