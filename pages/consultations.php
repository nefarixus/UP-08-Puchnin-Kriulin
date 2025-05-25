<?php include_once "../includes/parts/header.php"; ?>

<main>
    <h1 style="margin-bottom: 20px;">Консультации</h1>

    <form id="add-consultation-form" class="add_form">
        <input type="number" name="teacher_id" placeholder="ID преподавателя"><br>
        <input type="number" name="group_id" placeholder="ID группы"><br>
        <input type="number" name="student_id" placeholder="ID студента (опционально)"><br>
        <input type="date" name="consultation_date"><br>
        <input type="number" name="is_present" min="0" max="1" placeholder="Присутствовал (0 или 1)"><br>
        <input type="number" name="is_completed" min='0' max="1" placeholder="Завершено (0 или 1)"><br>
        <button class="add-students-btn" type="submit">Добавить</button>
    </form>

    <h2 style="margin-top:15px;">Список консультаций</h2>
    <table id="consultations-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID преподавателя</th>
                <th>ID группы</th>
                <th>ID студента</th>
                <th>Дата</th>
                <th>Присутствует</th>
                <th>Завершена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</main>

<?php include_once "../includes/parts/footer.php"; ?>