window.onerror = function(message, source, lineno, colno, error) {
    $.post('/UP-08-Puchnin-Kriulin/controllers/api/log_error.php', {
        message: message,
        stack: error?.stack || ''
    });
    return false;
};
$(document).ready(function () {
    
   // --- STUDENTS ---
    const tableBodyStudents = $('#students-table tbody');
    const studentSearchInput = $('#student-search-input');
    const sortGroupSelect = $('#sort-group-select');
    const sortDismissalSelect = $('#sort-dismissal-select');

    let currentSearch = '';
    let currentGroupFilter = '';
    let currentDismissalFilter = '';

    function loadStudents() {
        let url = '/UP-08-Puchnin-Kriulin/controllers/api/student_api.php';
        let params = {};

        if (currentSearch) params.search = currentSearch;
        if (currentGroupFilter) params.group_id = currentGroupFilter;
        if (currentDismissalFilter) params.dismissal = currentDismissalFilter;

        $.get(url, params, function(data) {
            renderStudents(data);
        }, 'json');
    }

    function renderStudents(data) {
        tableBodyStudents.empty();

        data.forEach(student => {
            const row = `
                <tr data-id="${student.student_id}">
                    <td>${student.student_id}</td>
                    <td contenteditable="true" class="edit-last-name">${student.last_name}</td>
                    <td contenteditable="true" class="edit-first-name">${student.first_name}</td>
                    <td contenteditable="true" class="edit-middle-name">${student.middle_name || ''}</td>
                    <td contenteditable="true" class="edit-group-id" data-group-id="${student.group_id}">
                        ${student.group_name || '—'}
                    </td>
                    <td contenteditable="true" class="edit-dismissal-date">${student.dismissal_date || ''}</td>
                    <td>
                        <button class="save-btn save-btn-students">Сохранить</button>
                        <button class="delete-btn delete-btn-students">Удалить</button>
                    </td>
                </tr>`;
            tableBodyStudents.append(row);
        });
    }

    // --- Поиск по ФИО ---
    if (studentSearchInput.length > 0) {
        studentSearchInput.on('input', function () {
            currentSearch = $(this).val().trim();
            loadStudents();
        });
    }

    // --- Сортировка по группе ---
    if (sortGroupSelect.length > 0) {
        sortGroupSelect.on('change', function () {
            currentGroupFilter = $(this).val().trim();
            loadStudents();
        });
    }

    // --- Сортировка по дате отчисления ---
    if (sortDismissalSelect.length > 0) {
        sortDismissalSelect.on('change', function () {
            currentDismissalFilter = $(this).val().trim();
            loadStudents();
        });
    }

    // --- Загрузка при старте ---
    if ($('#students-table').length > 0) {
        loadStudents();
        loadStudentGroups();
    }

    // --- Добавление студента ---
    $('#add-student-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };

        formData.forEach(field => {
            data[field.name] = field.value;
        });

        // Если дата пустая — отправляем null
        if (data.dismissal_date === '') {
            data.dismissal_date = null;
        }

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', JSON.stringify(data))
            .done(function () {
                $('#add-student-form')[0].reset();
                loadStudents();
            })
            .fail(function (xhr) {
                alert(xhr.responseJSON.errors.join('\n'));
            });
    });

    // --- Сохранение изменений ---
    $(document).on('click', '.save-btn-students', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const student = {
            action: 'update',
            student_id: id,
            last_name: row.find('.edit-last-name').text(),
            first_name: row.find('.edit-first-name').text(),
            middle_name: row.find('.edit-middle-name').text() || null,
            group_id: row.find('.edit-group-id').data('group-id'),
            dismissal_date: row.find('.edit-dismissal-date').text() || null
        };

        // Пользователь может попытаться ввести новое значение group_id
        const newGroupIdText = row.find('.edit-group-id').text().trim();
        const newGroupId = parseInt(newGroupIdText);

        if (!isNaN(newGroupId)) {
            student.group_id = newGroupId;
        }

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', JSON.stringify(student))
            .done(function () {
                loadStudents();
            })
            .fail(function (xhr) {
                alert(xhr.responseJSON.errors.join('\n'));
            });
    });

    // --- Удаление студента ---
    $(document).on('click', '.delete-btn-students', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', student_id: id };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', JSON.stringify(data))
            .done(function () {
                loadStudents();
            })
            .fail(function (xhr) {
                alert(xhr.responseJSON.message);
            });
    });

    // --- Сброс фильтров ---
    $('#reset-student-filters').on('click', function () {
        currentSearch = '';
        currentGroupFilter = '';
        currentDismissalFilter = '';
        studentSearchInput.val('');
        sortGroupSelect.val('');
        sortDismissalSelect.val('');
        loadStudents();
    });

    // --- Загрузка групп для фильтрации ---
    function loadStudentGroups() {
        const groupSelect = $('#sort-group-select');
        if (groupSelect.length === 0) return;

        $.get('/UP-08-Puchnin-Kriulin/controllers/api/group_api.php', function(data) {
            groupSelect.empty().append('<option value="">Все группы</option>');

            data.forEach(group => {
                groupSelect.append(`<option value="${group.group_id}">${group.group_name}</option>`);
            });
        }, 'json');
    }




    // --- DISCIPLINES ---
    const tableBodyDisciplines = $('#disciplines-table tbody');
    let currentDisciplineSearch = '';

    function loadDisciplines() {
        const url = '/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php';

        if (currentDisciplineSearch.trim() !== '') {
            $.get(url + '?search=' + encodeURIComponent(currentDisciplineSearch), function(data) {
                renderDisciplines(data);
            }, 'json');
        } else {
            $.get(url, function(data) {
                renderDisciplines(data);
            }, 'json');
        }
    }

    function renderDisciplines(data) {
        tableBodyDisciplines.empty();

        data.forEach(discipline => {
            const row = `
                <tr data-id="${discipline.discipline_id}">
                    <td>${discipline.discipline_id}</td>
                    <td contenteditable="true" class="edit-discipline-name">${discipline.discipline_name || ''}</td>
                    <td>
                        <button class="save-btn save-btn-disciplines">Сохранить</button>
                        <button class="delete-btn delete-btn-disciplines">Удалить</button>
                    </td>
                </tr>`;
            tableBodyDisciplines.append(row);
        });
    }

    $('#discipline-search-input').on('input', function () {
        currentDisciplineSearch = $(this).val().trim();
        loadDisciplines();
    });

    $('#reset-discipline-filters').on('click', function () {
        $('#discipline-search-input').val('');
        currentDisciplineSearch = '';
        loadDisciplines();
    });

    $('#add-discipline-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };

        formData.forEach(field => data[field.name] = field.value);

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', JSON.stringify(data))
            .done(function () {
                $('#add-discipline-form')[0].reset();
                loadDisciplines();
            })
            .fail(function (xhr) {
                alert(xhr.responseJSON.message || 'Ошибка при добавлении дисциплины');
            });
    });

    $(document).on('click', '.save-btn-disciplines', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const discipline = {
            action: 'update',
            discipline_id: id,
            discipline_name: row.find('.edit-discipline-name').text()
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', JSON.stringify(discipline))
            .done(() => loadDisciplines())
            .fail(() => alert('Ошибка при обновлении'));
    });

    $(document).on('click', '.delete-btn-disciplines', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', discipline_id: id };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', JSON.stringify(data))
            .done(() => loadDisciplines())
            .fail(xhr => alert(xhr.responseJSON.message || 'Нельзя удалить — есть связи'));
    });

    $(document).ready(function () {
        if ($('#disciplines-table').length > 0) {
            loadDisciplines();
        }

        if ($('#discipline-group-select').length > 0) {
            loadDisciplineOptions();
        }
    });

    function loadDisciplineOptions() {
        const select = $('#discipline-group-select');
        if (select.length === 0) return;

        $.get('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', function(data) {
            select.empty().append('<option value="">Выберите дисциплину</option>');
            data.forEach(discipline => {
                select.append(`<option value="${discipline.discipline_id}">${discipline.discipline_name}</option>`);
            });
        }, 'json');
    }

    function loadGroupInfoByDiscipline(disciplineId) {
        const tableBody = $('#discipline-students-table tbody');
        tableBody.empty();

        if (!disciplineId) {
            tableBody.append('<tr><td colspan="3">Выберите дисциплину</td></tr>');
            return;
        }

        $.get(`/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php?discipline_id=${disciplineId}`, function(data) {
            if (data.length === 0) {
                tableBody.append('<tr><td colspan="3">Нет данных по этой дисциплине</td></tr>');
                return;
            }

            data.forEach(item => {
                const row = `
                    <tr>
                        <td>${item.group_name || '—'}</td>
                        <td>${item.lesson_count || 0}</td>
                        <td>${item.total_hours || 0}</td>
                    </tr>`;
                tableBody.append(row);
            });
        }, 'json');
    }

    $(document).on('change', '#discipline-group-select', function () {
        const disciplineId = $(this).val();
        loadGroupInfoByDiscipline(disciplineId);
    });

    // --- ABSENCES ---
    const tableBodyAbsences = $('#absences-table tbody');
    let currentAbsencesSearch = '';

    function loadAbsences(filters = {}) {
        let url = '/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php';
        $.get(url + '?search=' + encodeURIComponent(currentAbsencesSearch), function(data) {
            renderAbsences(data);
        }, 'json')
        .fail(function(xhr) {
            alert('Ошибка при загрузке пропусков');
        });
    }

    function renderAbsences(data) {
        tableBodyAbsences.empty();

        data.forEach(absence => {
            const row = `
                <tr data-id="${absence.absence_id}">
                    <td>${absence.absence_id}</td>
                    <td contenteditable="true" class="edit-lesson-id">${absence.lesson_id || ''}</td>
                    <td contenteditable="true" class="edit-student-id">${absence.student_id || ''}</td>
                    <td>${absence.last_name || '—'}</td>
                    <td>${absence.group_name || '—'}</td>
                    <td contenteditable="true" class="edit-minutes-missed">${absence.minutes_missed || ''}</td>
                    <td>
                        ${absence.explanatory_note_path ? 
                            `<a class="download" href="/UP-08-Puchnin-Kriulin/absence_text/${absence.explanatory_note_path}" target="_blank">Скачать</a>` : 
                            '—'}
                    </td>
                    <td>
                        <button class="save-btn save-btn-absences">Сохранить</button>
                        <button class="delete-btn delete-btn-absences">Удалить</button>
                    </td>
                </tr>`;
            tableBodyAbsences.append(row);
        });
    }

    // --- Поиск ---
    $('#absence-search-input').on('input', function () {
        currentAbsencesSearch = $(this).val().trim();
        loadAbsences();
    });

    $('#reset-absence-filters').on('click', function () {
        $('#absence-search-input').val('');
        currentAbsencesSearch = '';
        loadAbsences();
    });

    // --- Загрузка при старте ---
    if ($('#absences-table').length > 0) {
        loadAbsences();
    }

    // --- Добавление пропуска ---
    $('#add-absence-form').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serializeArray();
        const data = { action: 'create' };
        formData.forEach(field => data[field.name] = field.value);

        const fileInput = $('#explanatory-note-file')[0];
        const file = fileInput.files[0];

        if (file && file.type === 'application/pdf') {
            const reader = new FileReader();
            reader.onload = function () {
                data.explanatory_note_path = file.name; // имя файла
                $.post('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', JSON.stringify(data), function () {
                    uploadFile(file); // отправляем файл на сервер
                    $('#add-absence-form')[0].reset();
                    loadAbsences();
                });
            };
            reader.readAsDataURL(file);
        } else if (file && file.type !== 'application/pdf') {
            alert('Только PDF-файлы разрешены');
        } else {
            $.post('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', JSON.stringify(data))
                .done(() => {
                    $('#add-absence-form')[0].reset();
                    loadAbsences();
                })
                .fail(xhr => alert(xhr.responseJSON.message || 'Ошибка при добавлении'));
        }
    });

    function uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);

        $.ajax({
            url: '/UP-08-Puchnin-Kriulin/upload_absence_file.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log('Файл успешно загружен:', response.path);
            },
            error: function () {
                alert('Ошибка загрузки файла');
            }
        });
    }

    // --- Сохранение изменений ---
    $(document).on('click', '.save-btn-absences', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');
        const absence = {
            action: 'update',
            absence_id: id,
            lesson_id: row.find('.edit-lesson-id').text().trim(),
            student_id: row.find('.edit-student-id').text().trim(),
            minutes_missed: row.find('.edit-minutes-missed').text().trim()
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', JSON.stringify(absence))
            .done(() => {
                loadAbsences();
            })
            .fail(xhr => {
                alert(xhr.responseJSON.message || 'Ошибка при обновлении');
            });
    });

    // --- Удаление пропуска ---
    $(document).on('click', '.delete-btn-absences', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', absence_id: id };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', JSON.stringify(data))
            .done(() => {
                loadAbsences();
            })
            .fail(xhr => {
                alert(xhr.responseJSON.message || 'Ошибка при удалении');
            });
    });


    // --- STUDENT GROUPS ---
    const tableBodyGroups = $('#groups-table tbody');
        let currentGroupSearch = '';

        function loadGroups(filters = {}) {
        let url = '/UP-08-Puchnin-Kriulin/controllers/api/group_api.php';

        if (filters.search) {
            url += `?search=${filters.search}`;
        }

        $.get(url, function(data) {
            renderGroups(data);
        }, 'json');
    }

    function renderGroups(data) {
        tableBodyGroups.empty();
        data.forEach(group => {
            const row = `
                <tr data-id="${group.group_id}">
                    <td>${group.group_id}</td>
                    <td contenteditable="true" class="edit-group-name">${group.group_name}</td>
                    <td>
                        <button class="save-btn save-btn-groups">Сохранить</button>
                        <button class="delete-btn delete-btn-groups">Удалить</button>
                    </td>
                </tr>`;
            tableBodyGroups.append(row);
        });
    }

    // --- Выпадающий список и просмотр студентов по группе ---
    function loadGroupOptions() {
        const groupSelect = $('#group-select-for-students');
        if (groupSelect.length === 0) return;

        $.get('/UP-08-Puchnin-Kriulin/controllers/api/group_api.php', function(data) {
            groupSelect.empty().append('<option value="">Выберите группу</option>');
            data.forEach(group => {
                groupSelect.append(`<option value="${group.group_id}">${group.group_name}</option>`);
            });
        }, 'json');
    }

    function loadStudentsByGroup(groupId) {
        const tableBody = $('#group-students-table tbody');
        tableBody.empty();

        if (!groupId) {
            tableBody.append('<tr><td colspan="4">Выберите группу</td></tr>');
            return;
        }

        $.get('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php?group_id=' + groupId, function(data) {
            if (data.length === 0) {
                tableBody.append('<tr><td colspan="4">В этой группе нет студентов</td></tr>');
                return;
            }

            data.forEach(student => {
                const row = `
                    <tr>
                        <td>${student.student_id}</td>
                        <td>${student.last_name}</td>
                        <td>${student.first_name}</td>
                        <td>${student.middle_name || ''}</td>
                    </tr>`;
                tableBody.append(row);
            });
        }, 'json');
    }

    if ($('#groups-table').length > 0) {
        loadGroups();
        loadGroupOptions();
    }

    $('#group-search-input').on('input', function () {
        currentSearch = $(this).val().trim();
        loadGroups({ search: currentSearch });
    });

    $('#reset-group-filters').on('click', function () {
        $('#group-search-input').val('');
        currentSearch = '';
        loadGroups();
    });

    $('#group-select-for-students').on('change', function () {
        const groupId = $(this).val();
        loadStudentsByGroup(groupId);
    });

    $('#add-group-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };

        formData.forEach(field => {
            data[field.name] = field.value;
        });

        if (data.dismissal_date === '') {
            data.dismissal_date = null;
        }

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/group_api.php', JSON.stringify(data))
            .done(function () {
                $('#add-group-form')[0].reset();
                loadGroups();
            })
            .fail(function (xhr) {
                alert(xhr.responseJSON.errors.join('\n'));
            });
    });

    $(document).on('click', '.delete-btn-groups', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', group_id: id };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/group_api.php', JSON.stringify(data), function () {
            loadGroups();
        }).fail(function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.message) {
                alert(xhr.responseJSON.message);
            } else {
                alert('Ошибка при удалении группы');
            }
        });
    });


    // --- DISCIPLINE PROGRAMS ---
    const tableBodyPrograms = $('#programs-table tbody');

    function loadPrograms() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/program_api.php', function (data) {
            tableBodyPrograms.empty();
            data.forEach(program => {
                const row = `
                    <tr data-id="${program.program_id}">
                        <td>${program.program_id}</td>
                        <td contenteditable="true" class="edit-discipline-id">${program.discipline_id}</td>
                        <td contenteditable="true" class="edit-topic">${program.topic}</td>
                        <td contenteditable="true" class="edit-lesson-type">${program.lesson_type}</td>
                        <td contenteditable="true" class="edit-hours">${program.hours}</td>
                        <td>
                            <button class="save-btn save-btn-programs">Сохранить</button>
                            <button class="delete-btn delete-btn-programs">Удалить</button>
                        </td>
                    </tr>`;
                tableBodyPrograms.append(row);
            });
        }, 'json');
    }

    if ($('#programs-table').length > 0) {
        loadPrograms();
    }

    $('#add-program-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };
        formData.forEach(field => data[field.name] = field.value);
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/program_api.php', JSON.stringify(data), function () {
            $('#add-program-form')[0].reset();
            loadPrograms();
        });
    });

    $(document).on('click', '.save-btn-programs', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');
        const program = {
            action: 'update',
            program_id: id,
            discipline_id: row.find('.edit-discipline-id').text(),
            topic: row.find('.edit-topic').text(),
            lesson_type: row.find('.edit-lesson-type').text(),
            hours: row.find('.edit-hours').text()
        };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/program_api.php', JSON.stringify(program), function () {
            loadPrograms();
        });
    });

    $(document).on('click', '.delete-btn-programs', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', program_id: id };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/program_api.php', JSON.stringify(data), function () {
            loadPrograms();
        });
    });


    // --- TEACHERS ---
    const tableBodyTeachers = $('#teachers-table tbody');

    function loadTeachers() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/teacher_api.php', function (data) {
            tableBodyTeachers.empty();
            data.forEach(teacher => {
                const row = `
                    <tr data-id="${teacher.teacher_id}">
                        <td>${teacher.teacher_id}</td>
                        <td contenteditable="true" class="edit-last-name">${teacher.last_name}</td>
                        <td contenteditable="true" class="edit-first-name">${teacher.first_name}</td>
                        <td contenteditable="true" class="edit-middle-name">${teacher.middle_name || ''}</td>
                        <td contenteditable="true" class="edit-login">${teacher.login}</td>
                        <td contenteditable="true" class="edit-password">${teacher.password}</td>
                        <td>
                            <button class="save-btn save-btn-teachers">Сохранить</button>
                            <button class="delete-btn delete-btn-teachers">Удалить</button>
                        </td>
                    </tr>`;
                tableBodyTeachers.append(row);
            });
        }, 'json');
    }

    if ($('#teachers-table').length > 0) {
        loadTeachers();
    }

    $('#add-teacher-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };
        formData.forEach(field => data[field.name] = field.value);
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/teacher_api.php', JSON.stringify(data), function () {
            $('#add-teacher-form')[0].reset();
            loadTeachers();
        });
    });

    $(document).on('click', '.save-btn-teachers', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');
        const teacher = {
            action: 'update',
            teacher_id: id,
            last_name: row.find('.edit-last-name').text(),
            first_name: row.find('.edit-first-name').text(),
            middle_name: row.find('.edit-middle-name').text() || null,
            login: row.find('.edit-login').text(),
            password: row.find('.edit-password').text()
        };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/teacher_api.php', JSON.stringify(teacher), function () {
            loadTeachers();
        });
    });

    $(document).on('click', '.delete-btn-teachers', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', teacher_id: id };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/teacher_api.php', JSON.stringify(data), function () {
            loadTeachers();
        });
    });
});


/*Grade */

$(document).ready(function () {
    const tableBodyGrades = $('#grades-table tbody');

    function loadGrades() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/grade_api.php', function (data) {
            tableBodyGrades.empty();
            data.forEach(grade => {
                const row = `
                    <tr data-id="${grade.grade_id}">
                        <td>${grade.grade_id}</td>
                        <td contenteditable="true" class="edit-lesson-id">${grade.lesson_id}</td>
                        <td contenteditable="true" class="edit-student-id">${grade.student_id}</td>
                        <td contenteditable="true" class="edit-grade-value">${grade.grade_value}</td>
                        <td>
                            <button class="save-btn save-btn-grades">Сохранить</button>
                            <button class="delete-btn delete-btn-grades">Удалить</button>
                        </td>
                    </tr>`;
                tableBodyGrades.append(row);
            });
        }, 'json');
    }

    if ($('#grades-table').length > 0) {
        loadGrades();
    }

    $('#add-grade-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };
        formData.forEach(field => data[field.name] = field.value);
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/grade_api.php', JSON.stringify(data), function () {
            $('#add-grade-form')[0].reset();
            loadGrades();
        });
    });

    $(document).on('click', '.save-btn-grades', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const grade = {
            action: 'update',
            grade_id: id,
            lesson_id: row.find('.edit-lesson-id').text(),
            student_id: row.find('.edit-student-id').text(),
            grade_value: row.find('.edit-grade-value').text()
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/grade_api.php', JSON.stringify(grade), function () {
            loadGrades();
        });
    });

    $(document).on('click', '.delete-btn-grades', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', grade_id: id };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/grade_api.php', JSON.stringify(data), function () {
            loadGrades();
        });
    });
});

/* consultation */
$(document).ready(function () {
    const tableBody = $('#consultations-table tbody');

    function loadConsultations() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/consultation_api.php', function (data) {
            tableBody.empty();
            data.forEach(item => {
                const row = `
                    <tr data-id="${item.consultation_id}">
                        <td>${item.consultation_id}</td>
                        <td contenteditable="true" class="edit-teacher-id">${item.teacher_id}</td>
                        <td contenteditable="true" class="edit-group-id">${item.group_id}</td>
                        <td contenteditable="true" class="edit-student-id">${item.student_id || ''}</td>
                        <td contenteditable="true" class="edit-date">${item.consultation_date}</td>
                        <td contenteditable="true" class="edit-is-present">${item.is_present}</td>
                        <td contenteditable="true" class="edit-is-completed">${item.is_completed}</td>
                        <td>
                            <button class="save-btn save-btn-consultation">Сохранить</button>
                            <button class="delete-btn delete-btn-consultation">Удалить</button>
                        </td>
                    </tr>`;
                tableBody.append(row);
            });
        }, 'json');
    }

    if ($('#consultations-table').length > 0) {
        loadConsultations();
    }

    $('#add-consultation-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };
        formData.forEach(field => data[field.name] = field.value);
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/consultation_api.php', JSON.stringify(data), function () {
            $('#add-consultation-form')[0].reset();
            loadConsultations();
        });
    });

    $(document).on('click', '.save-btn-consultation', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const item = {
            action: 'update',
            consultation_id: id,
            teacher_id: row.find('.edit-teacher-id').text(),
            group_id: row.find('.edit-group-id').text(),
            student_id: row.find('.edit-student-id').text(),
            consultation_date: row.find('.edit-date').text(),
            is_present: row.find('.edit-is-present').text(),
            is_completed: row.find('.edit-is-completed').text()
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/consultation_api.php', JSON.stringify(item), function () {
            loadConsultations();
        });
    });

    $(document).on('click', '.delete-btn-consultation', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', consultation_id: id };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/consultation_api.php', JSON.stringify(data), function () {
            loadConsultations();
        });
    });
});

/* workload */

$(document).ready(function () {
    const tableBody = $('#workload-table tbody');

    function loadWorkloads() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/workload_api.php', function (data) {
            tableBody.empty();
            data.forEach(item => {
                const row = `
                    <tr data-id="${item.workload_id}">
                        <td>${item.workload_id}</td>
                        <td contenteditable="true" class="edit-teacher-id">${item.teacher_id}</td>
                        <td contenteditable="true" class="edit-discipline-id">${item.discipline_id}</td>
                        <td contenteditable="true" class="edit-group-id">${item.group_id}</td>
                        <td contenteditable="true" class="edit-lecture-hours">${item.lecture_hours}</td>
                        <td contenteditable="true" class="edit-practice-hours">${item.practice_hours}</td>
                        <td contenteditable="true" class="edit-consultation-hours">${item.consultation_hours}</td>
                        <td contenteditable="true" class="edit-course-project-hours">${item.course_project_hours}</td>
                        <td contenteditable="true" class="edit-exam-hours">${item.exam_hours}</td>
                        <td>
                            <button class="save-btn save-btn-workload">Сохранить</button>
                            <button class="delete-btn delete-btn-workload">Удалить</button>
                        </td>
                    </tr>`;
                tableBody.append(row);
            });
        }, 'json');
    }

    if ($('#workload-table').length > 0) {
        loadWorkloads();
    }

    $('#add-workload-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };
        formData.forEach(field => data[field.name] = field.value);
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/workload_api.php', JSON.stringify(data), function () {
            $('#add-workload-form')[0].reset();
            loadWorkloads();
        });
    });

    $(document).on('click', '.save-btn-workload', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const item = {
            action: 'update',
            workload_id: id,
            teacher_id: row.find('.edit-teacher-id').text(),
            discipline_id: row.find('.edit-discipline-id').text(),
            group_id: row.find('.edit-group-id').text(),
            lecture_hours: row.find('.edit-lecture-hours').text(),
            practice_hours: row.find('.edit-practice-hours').text(),
            consultation_hours: row.find('.edit-consultation-hours').text(),
            course_project_hours: row.find('.edit-course-project-hours').text(),
            exam_hours: row.find('.edit-exam-hours').text()
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/workload_api.php', JSON.stringify(item), function () {
            loadWorkloads();
        });
    });

    $(document).on('click', '.delete-btn-workload', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', workload_id: id };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/workload_api.php', JSON.stringify(data), function () {
            loadWorkloads();
        });
    });
});

/* lesson */

$(document).ready(function () {
    const tableBody = $('#lessons-table tbody');

    function loadLessons() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/lesson_api.php', function (data) {
            tableBody.empty();
            data.forEach(item => {
                const row = `
                    <tr data-id="${item.lesson_id}">
                        <td>${item.lesson_id}</td>
                        <td contenteditable="true" class="edit-program-id">${item.program_id}</td>
                        <td contenteditable="true" class="edit-group-id">${item.group_id}</td>
                        <td contenteditable="true" class="edit-teacher-id">${item.teacher_id}</td>
                        <td contenteditable="true" class="edit-lesson-date">${item.lesson_date}</td>
                        <td contenteditable="true" class="edit-duration-minutes">${item.duration_minutes}</td>
                        <td>
                            <button class="save-btn save-btn-lesson">Сохранить</button>
                            <button class="delete-btn delete-btn-lesson">Удалить</button>
                        </td>
                    </tr>`;
                tableBody.append(row);
            });
        }, 'json');
    }

    if ($('#lessons-table').length > 0) {
        loadLessons();
    }

    $('#add-lesson-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };
        formData.forEach(field => data[field.name] = field.value);
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/lesson_api.php', JSON.stringify(data), function () {
            $('#add-lesson-form')[0].reset();
            loadLessons();
        });
    });

    $(document).on('click', '.save-btn-lesson', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const item = {
            action: 'update',
            lesson_id: id,
            program_id: row.find('.edit-program-id').text(),
            group_id: row.find('.edit-group-id').text(),
            teacher_id: row.find('.edit-teacher-id').text(),
            lesson_date: row.find('.edit-lesson-date').text(),
            duration_minutes: row.find('.edit-duration-minutes').text()
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/lesson_api.php', JSON.stringify(item), function () {
            loadLessons();
        });
    });

    $(document).on('click', '.delete-btn-lesson', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', lesson_id: id };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/lesson_api.php', JSON.stringify(data), function () {
            loadLessons();
        });
    });
});