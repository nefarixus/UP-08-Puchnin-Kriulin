$(document).ready(function () {
    const tableBody = $('#students-table tbody');

    function loadStudents() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', function (data) {
            tableBody.empty();

            data.forEach(student => {
                const row = `
                    <tr data-id="${student.student_id}">
                        <td>${student.student_id}</td>
                        <td contenteditable="true" class="edit-last-name">${student.last_name}</td>
                        <td contenteditable="true" class="edit-first-name">${student.first_name}</td>
                        <td contenteditable="true" class="edit-middle-name">${student.middle_name || ''}</td>
                        <td contenteditable="true" class="edit-group-id">${student.group_id || ''}</td>
                        <td contenteditable="true" class="edit-dismissal-date">${student.dismissal_date || ''}</td>
                        <td>
                            <button class="save-btn edit-students-btn">Сохранить</button>
                            <button class="delete-btn delete-students-btn">Удалить</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });
        }, 'json');
    }

    // Загрузка при открытии страницы
    loadStudents();

    // Добавление студента
    $('#add-student-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();

        const data = {
            action: 'create'
        };

        formData.forEach(field => {
            data[field.name] = field.value;
        });

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', JSON.stringify(data), function () {
            $('#add-student-form')[0].reset();
            loadStudents();
        });
    });

    // Сохранение изменений при нажатии на "Сохранить"
    $(document).on('click', '.save-btn', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const student = {
            action: 'update',
            student_id: id,
            last_name: row.find('.edit-last-name').text(),
            first_name: row.find('.edit-first-name').text(),
            middle_name: row.find('.edit-middle-name').text() || null,
            group_id: row.find('.edit-group-id').text() || null,
            dismissal_date: row.find('.edit-dismissal-date').text() || null
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', JSON.stringify(student), function () {
            loadStudents();
        });
    });

    // Удаление студента
    $(document).on('click', '.delete-btn', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const data = {
            action: 'delete',
            student_id: id
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', JSON.stringify(data), function () {
            loadStudents();
        });
    });

    // --- Дисциплины ---
    const tableBodyDisciplines = $('#disciplines-table tbody');

    function loadDisciplines() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', function (data) {
            tableBodyDisciplines.empty();

            data.forEach(discipline => {
                const row = `
                    <tr data-id="${discipline.discipline_id}">
                        <td>${discipline.discipline_id}</td>
                        <td contenteditable="true" class="edit-discipline-name">${discipline.discipline_name}</td>
                        <td>
                            <button class="save-btn edit-students-btn">Сохранить</button>
                            <button class="delete-btn delete-students-btn">Удалить</button>
                        </td>
                    </tr>
                `;
                tableBodyDisciplines.append(row);
            });
        }, 'json');
    }

    // Загрузка при открытии страницы
    if ($('#disciplines-table').length > 0) {
        loadDisciplines();
    }

    // Добавление дисциплины
    $('#add-discipline-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();

        const data = { action: 'create' };
        formData.forEach(field => {
            data[field.name] = field.value;
        });

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', JSON.stringify(data), function () {
            $('#add-discipline-form')[0].reset();
            loadDisciplines();
        });
    });

    // Сохранение изменений
    $(document).on('click', '.save-btn', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const discipline = {
            action: 'update',
            discipline_id: id,
            discipline_name: row.find('.edit-discipline-name').text()
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', JSON.stringify(discipline), function () {
            loadDisciplines();
        });
    });

    // Удаление дисциплины
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).closest('tr').data('id');

        const data = {
            action: 'delete',
            discipline_id: id
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', JSON.stringify(data), function () {
            loadDisciplines();
        });
    });

    const tableBodyAbsences = $('#absences-table tbody');

    function loadAbsences() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', function (data) {
            tableBodyAbsences.empty();

            data.forEach(absence => {
                const row = `
                    <tr data-id="${absence.absence_id}">
                        <td>${absence.absence_id}</td>
                        <td contenteditable="true" class="edit-lesson-id">${absence.lesson_id}</td>
                        <td contenteditable="true" class="edit-student-id">${absence.student_id}</td>
                        <td contenteditable="true" class="edit-minutes-missed">${absence.minutes_missed}</td>
                        <td contenteditable="true" class="edit-explanatory-note-path">${absence.explanatory_note_path || ''}</td>
                        <td>
                            <button class="save-btn edit-students-btn">Сохранить</button>
                            <button class="delete-btn delete-students-btn">Удалить</button>
                        </td>
                    </tr>`;
                tableBodyAbsences.append(row);
            });
        }, 'json');
    }

    // Автозагрузка
    if ($('#absences-table').length > 0) {
        loadAbsences();
    }

    // Добавление
    $('#add-absence-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };
        formData.forEach(field => data[field.name] = field.value);
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', JSON.stringify(data), function () {
            $('#add-absence-form')[0].reset();
            loadAbsences();
        });
    });

    // Обновление
    $(document).on('click', '.save-btn', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const absence = {
            action: 'update',
            absence_id: id,
            lesson_id: row.find('.edit-lesson-id').text(),
            student_id: row.find('.edit-student-id').text(),
            minutes_missed: row.find('.edit-minutes-missed').text(),
            explanatory_note_path: row.find('.edit-explanatory-note-path').text() || null
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', JSON.stringify(absence), function () {
            loadAbsences();
        });
    });

    // Удаление
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', absence_id: id };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', JSON.stringify(data), function () {
            loadAbsences();
        });
    });
});