<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1>Преподаватели</h1>

    <h2 style="margin-top: 15px;">Добавить преподавателя</h2>
    <form id="add-teacher-form" class="add_form">
        <input type="text" name="last_name" placeholder="Фамилия"><br>
        <input type="text" name="first_name" placeholder="Имя"><br>
        <input type="text" name="middle_name" placeholder="Отчество"><br>
        <input type="text" name="login" placeholder="Логин"><br>
        <input type="text" name="password" placeholder="Пароль"><br>
        <button type="submit" class="add-teachers-btn">Добавить</button>
    </form>

    <h2 style="margin-top: 15px;">Список преподавателей</h2>
    <table id="teachers-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
                <th>Логин</th>
                <th>Пароль</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<?php include_once "../includes/parts/footer.php"; ?>