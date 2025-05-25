<?php include_once "../includes/parts/header.php"; ?>
    <main>
        <h1>Дисциплины</h1>

        <!-- Форма добавления -->
        <h2 style="margin-top: 15px;">Добавить дисциплину</h2>
        <form id="add-discipline-form" class="add_form">
            <input type="text" name="discipline_name" placeholder="Название дисциплины" required><br>
            <button type="submit" class="add-disciplines-btn">Добавить</button>
        </form>

        <!-- Список дисциплин -->
        <h2 style="margin-top: 15px;">Список дисциплин</h2>
        <table id="disciplines-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </main>
<?php include_once "../includes/parts/footer.php"; ?>