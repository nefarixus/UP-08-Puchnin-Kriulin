window.onerror = function(message, source, lineno, colno, error) {
    $.post('/UP-08-Puchnin-Kriulin/controllers/api/log_error.php', {
        message: message,
        stack: error?.stack || ''
    });
    return false;
};
$(document).ready(function () {
    if ($('#students-table').length > 0) loadStudents();
    if ($('#groups-table').length > 0) loadGroups();
    if ($('#disciplines-table').length > 0) loadDisciplines();
    if ($('#absences-table').length > 0) loadAbsences();
    if ($('#programs-table').length > 0) loadPrograms();
});

// --- STUDENTS ---
const tableBodyStudents = $('#students-table tbody');
let currentStudentSearch = '';

function loadStudents(filters = {}) {
    const url = '/UP-08-Puchnin-Kriulin/controllers/api/student_api.php';

    if (filters.search && filters.search.trim() !== '') {
        $.post(url, { search: filters.search }, function (data) {
            renderStudents(data);
        }, 'json');
    } else {
        $.get(url, function (data) {
            renderStudents(data);
        }, 'json');
    }
}

function renderStudents(data) {
    tableBodyStudents.empty();

    let items = Array.isArray(data) ? data : [];

    if (items.length === 0) {
        tableBodyStudents.append('<tr><td colspan="6">Нет данных</td></tr>');
        return;
    }

    items.forEach(student => {
        const row = `
            <tr data-id="${student.student_id}">
                <td>${student.student_id}</td>
                <td contenteditable="true" class="edit-last-name">${student.last_name || ''}</td>
                <td contenteditable="true" class="edit-first-name">${student.first_name || ''}</td>
                <td contenteditable="true" class="edit-middle-name">${student.middle_name || ''}</td>
                <td contenteditable="true" class="edit-group-id">${student.group_id || ''}</td>
                <td contenteditable="true" class="edit-dismissal-date">${student.dismissal_date || ''}</td>
                <td>
                    <button class="save-btn save-btn-students">Сохранить</button>
                    <button class="delete-btn delete-btn-students">Удалить</button>
                </td>
            </tr>`;
        tableBodyStudents.append(row);
    });
}

$('#add-student-form').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serializeArray();
    const data = { action: 'create' };
    formData.forEach(field => data[field.name] = field.value);

    $.post('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', JSON.stringify(data))
        .done(() => {
            $('#add-student-form')[0].reset();
            loadStudents();
        })
        .fail(xhr => {
            alert(xhr.responseJSON.message || 'Ошибка при добавлении студента');
        });
});

$(document).on('click', '.save-btn-students', function () {
    const row = $(this).closest('tr');
    const id = row.data('id');

    const student = {
        action: 'update',
        student_id: id,
        last_name: row.find('.edit-last-name').text().trim(),
        first_name: row.find('.edit-first-name').text().trim(),
        middle_name: row.find('.edit-middle-name').text().trim(),
        group_id: row.find('.edit-group-id').text().trim(),
        dismissal_date: row.find('.edit-dismissal-date').text().trim()
    };

    $.post('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', JSON.stringify(student))
        .done(() => loadStudents())
        .fail(xhr => {
            alert(xhr.responseJSON.message || 'Ошибка при обновлении');
        });
});

$(document).on('click', '.delete-btn-students', function () {
    const id = $(this).closest('tr').data('id');
    const data = { action: 'delete', student_id: id };

    $.post('/UP-08-Puchnin-Kriulin/controllers/api/student_api.php', JSON.stringify(data))
        .done(() => loadStudents())
        .fail(xhr => {
            alert(xhr.responseJSON.message || 'Нельзя удалить — есть связи');
        });
});

$('#student-search-input').on('input', function () {
    currentStudentSearch = $(this).val().trim();
    loadStudents({ search: currentStudentSearch });
});

$('#reset-student-filters').on('click', function () {
    $('#student-search-input').val('');
    currentStudentSearch = '';
    loadStudents();
});

// --- DISCIPLINES ---
const tableBodyDisciplines = $('#disciplines-table tbody');
let currentDisciplineSearch = '';

function loadDisciplines(filters = {}) {
    const url = '/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php';

    if (filters.search && filters.search.trim() !== '') {
        $.post(url, { search: filters.search }, function (data) {
            renderDisciplines(data);
        }, 'json');
    } else {
        $.get(url, function (data) {
            renderDisciplines(data);
        }, 'json');
    }
}

function renderDisciplines(data) {
    tableBodyDisciplines.empty();

    let items = Array.isArray(data) ? data : [];

    if (items.length === 0) {
        tableBodyDisciplines.append('<tr><td colspan="3">Нет данных</td></tr>');
        return;
    }

    items.forEach(discipline => {
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

$('#add-discipline-form').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serializeArray();
    const data = { action: 'create' };
    formData.forEach(field => data[field.name] = field.value);

    $.post('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', JSON.stringify(data))
        .done(() => {
            $('#add-discipline-form')[0].reset();
            loadDisciplines();
        })
        .fail(xhr => {
            alert(xhr.responseJSON.message || 'Ошибка при добавлении дисциплины');
        });
});

$(document).on('click', '.save-btn-disciplines', function () {
    const row = $(this).closest('tr');
    const program = {
        action: 'update',
        discipline_id: row.data('id'),
        discipline_name: row.find('.edit-discipline-name').text().trim()
    };

    $.post('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', JSON.stringify(program))
        .done(() => loadDisciplines())
        .fail(xhr => {
            alert(xhr.responseJSON.message || 'Ошибка при обновлении');
        });
});

$(document).on('click', '.delete-btn-disciplines', function () {
    const id = $(this).closest('tr').data('id');
    const data = { action: 'delete', discipline_id: id };

    $.post('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', JSON.stringify(data))
        .done(() => loadDisciplines())
        .fail(xhr => {
            alert(xhr.responseJSON.message || 'Нельзя удалить — есть нагрузка');
        });
});

// --- ABSENCES ---
const tableBodyAbsences = $('#absences-table tbody');
let currentAbsencesSearch = '';

function loadAbsences(filters = {}) {
    const url = '/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php';

    if (filters.search && filters.search.trim() !== '') {
        $.post(url, { search: filters.search }, function (data) {
            renderAbsences(data);
        }, 'json');
    } else {
        $.get(url, function (data) {
            renderAbsences(data);
        }, 'json');
    }
}

function renderAbsences(data) {
    tableBodyAbsences.empty();

    let items = Array.isArray(data) ? data : [];

    if (items.length === 0) {
        tableBodyAbsences.append('<tr><td colspan="7">Нет данных</td></tr>');
        return;
    }

    items.forEach(absence => {
        const row = `
            <tr data-id="${absence.absence_id}">
                <td>${absence.absence_id}</td>
                <td contenteditable="true" class="edit-lesson-id">${absence.lesson_id || ''}</td>
                <td contenteditable="true" class="edit-student-id">${absence.student_id || ''}</td>
                <td>${absence.last_name || '—'}</td>
                <td>${absence.group_name || '—'}</td>
                <td contenteditable="true" class="edit-minutes-missed">${absence.minutes_missed || ''}</td>
                <td>
                    ${absence.explanatory_note_path ? `<a href="/UP-08-Puchnin-Kriulin/absence_text/${absence.explanatory_note_path}" target="_blank">Скачать</a>` : '—'}
                </td>
                <td>
                    <button class="save-btn save-btn-absences">Сохранить</button>
                    <button class="delete-btn delete-btn-absences">Удалить</button>
                </td>
            </tr>`;
        tableBodyAbsences.append(row);
    });
}

$('#add-absence-form').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serializeArray();
    const data = { action: 'create' };
    formData.forEach(field => data[field.name] = field.value);

    const fileInput = $('#explanatory-note-file')[0];
    const file = fileInput.files[0];

    if (file && file.type === 'application/pdf') {
        data.explanatory_note_path = file.name;

        const reader = new FileReader();
        reader.onload = function () {
            $.post('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', JSON.stringify(data), function () {
                uploadFile(file);
                $('#add-absence-form')[0].reset();
                loadAbsences();
            });
        };
        reader.readAsDataURL(file);
    } else if (!file || file.type !== 'application/pdf') {
        alert('Только PDF-файлы разрешены');
    } else {
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/absence_api.php', JSON.stringify(data))
            .done(() => {
                $('#add-absence-form')[0].reset();
                loadAbsences();
            })
            .fail(xhr => {
                alert(xhr.responseJSON.message || 'Ошибка при добавлении пропуска');
            });
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
            console.log('Файл успешно загружен:', response);
        },
        error: function (xhr) {
            alert('Ошибка загрузки файла');
            console.error(xhr.responseText);
        }
    });
}

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

function loadPrograms(filters = {}) {
    const url = '/UP-08-Puchnin-Kriulin/controllers/api/program_api.php';

    if (filters.search && filters.search.trim() !== '') {
        $.post(url, { search: filters.search }, function(data) {
            renderPrograms(data);
        }, 'json')
        .fail(xhr => {
            alert(xhr.responseJSON.message || 'Ошибка при поиске программы');
        });
    } else if (filters.discipline_id) {
        $.get(url + '?discipline_id=' + filters.discipline_id, function(data) {
            renderPrograms(data);
        }, 'json');
    } else {
        $.get(url, function(data) {
            renderPrograms(data);
        }, 'json');
    }
}

function renderPrograms(data) {
    tableBodyPrograms.empty();

    let items = data.status === 'success' ? data.data : data;

    if (!Array.isArray(items)) {
        console.error('Ожидался массив:', items);
        alert('Ошибка: получены некорректные данные');
        return;
    }

    if (items.length === 0) {
        tableBodyPrograms.append('<tr><td colspan="6">Нет данных</td></tr>');
        return;
    }

    items.forEach(program => {
        const row = `
            <tr data-id="${program.program_id}">
                <td>${program.program_id}</td>
                <td class="edit-discipline-id">${program.discipline_name || '—'}</td>
                <td contenteditable="true" class="edit-topic">${program.topic || ''}</td>
                <td contenteditable="true" class="edit-lesson-type">${program.lesson_type || ''}</td>
                <td contenteditable="true" class="edit-hours">${program.hours || ''}</td>
                <td>
                    <button class="save-btn save-btn-programs">Сохранить</button>
                    <button class="delete-btn delete-btn-programs">Удалить</button>
                </td>
            </tr>`;
        tableBodyPrograms.append(row);
    });
}

function loadDisciplineOptionsForProgram() {
    const select = $('#program-discipline-select');
    if (select.length === 0) return;

    $.get('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', function(data) {
        select.empty().append('<option class="discipline-option-select" value="">Выберите дисциплину</option>');
        data.forEach(discipline => {
            select.append(`<option value="${discipline.discipline_id}">${discipline.discipline_name}</option>`);
        });
    }, 'json');
}

$('#add-program-form').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serializeArray();
    const data = { action: 'create' };

    formData.forEach(field => {
        data[field.name] = field.value;
    });

    // Защита от пустых значений
    if (!data.discipline_id || data.discipline_id === '') {
        alert('Выберите дисциплину');
        return;
    }

    $.post('/UP-08-Puchnin-Kriulin/controllers/api/program_api.php', JSON.stringify(data))
        .done(() => {
            $('#add-program-form')[0].reset();
            loadPrograms();
        })
        .fail(xhr => {
            alert(xhr.responseJSON.message || 'Ошибка при добавлении программы');
        });
});

$(document).ready(function () {
    if ($('#add-program-form').length > 0) {
        loadDisciplineOptionsForProgram();
    }
});

$(document).on('click', '.save-btn-programs', function () {
    const row = $(this).closest('tr');
    const programId = row.data('id');

    // Создаём выпадающий список
    const disciplineSelect = $('<select>')
        .addClass('edit-discipline-id')
        .append($('<option>').val('').text('-- Выберите дисциплину --'));

    // Подгружаем все дисциплины
    $.get('/UP-08-Puchnin-Kriulin/controllers/api/discipline_api.php', function(data) {
        data.forEach(discipline => {
            const selected = discipline.discipline_id == row.find('.edit-discipline-id').text().trim()
                ? 'selected'
                : '';
            disciplineSelect.append(
                `<option value="${discipline.discipline_id}" ${selected}>${discipline.discipline_name}</option>`
            );
        });

        row.find('.edit-discipline-id').replaceWith(disciplineSelect);
    }, 'json');

    // Обработка изменения выпадающего списка
    row.find('.edit-discipline-id').on('change', function () {
        const updatedData = {
            action: 'update',
            program_id: programId,
            discipline_id: disciplineSelect.val(),
            topic: row.find('.edit-topic').text().trim(),
            lesson_type: row.find('.edit-lesson-type').text().trim(),
            hours: row.find('.edit-hours').text().trim()
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/program_api.php', JSON.stringify(updatedData))
            .done(() => loadPrograms())
            .fail(() => alert('Ошибка при обновлении'));
    });
});

$(document).on('click', '.delete-btn-programs', function () {
    const id = $(this).closest('tr').data('id');
    const data = { action: 'delete', program_id: id };

    $.post('/UP-08-Puchnin-Kriulin/controllers/api/program_api.php', JSON.stringify(data))
        .done(() => loadPrograms())
        .fail(xhr => {
            alert(xhr.responseJSON.message || 'Нельзя удалить — есть связанные занятия');
        });
});

    // --- TEACHERS ---
    const tableBodyTeachers = $('#teachers-table tbody');
    const teacherSearchInput = $('#teacher-search-input');

    let currentTeacherLoginFilter = '';

function loadTeachers() {
    let url = '/UP-08-Puchnin-Kriulin/controllers/api/teacher_api.php';
    let params = {};

    if (currentTeacherLoginFilter) {
        params.login = currentTeacherLoginFilter;
    }

    $.get(url, params, function (data) {
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
    }, 'json').fail(function (xhr) {
        console.error("Ошибка загрузки данных", xhr);
        alert("Не удалось загрузить список преподавателей");
    });
}

    // --- Поиск по логину ---
    if (teacherSearchInput.length > 0) {
        teacherSearchInput.on('input', function () {
            currentTeacherLoginFilter = $(this).val().trim();
            loadTeachers();
        });
    }

    // --- Сброс фильтров ---
    $('#reset-teacher-filters').on('click', function () {
        currentTeacherLoginFilter = '';
        teacherSearchInput.val('');
        loadTeachers();
    });

    // --- Загрузка при старте ---
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


/*Grade */

$(document).ready(function () {
    const tableBodyGrades = $('#grades-table tbody');


    function getColorClass(gradeValue) {
        switch (parseInt(gradeValue)) {
            case 5:
                return 'green';
            case 4:
                return 'dark-yellow';
            case 3:
                return 'orange';
            default:
                return 'gray';
        }
    }

    function loadGrades() {
        $.get('/UP-08-Puchnin-Kriulin/controllers/api/grade_api.php', function (data) {
            tableBodyGrades.empty();

            if (!Array.isArray(data)) {
                console.error("Неверный формат данных:", data);
                return;
            }

            data.forEach(grade => {
                const gradeClass = getColorClass(grade.grade_value); 

                const row = `
                    <tr data-id="${grade.grade_id}">
                        <td>${grade.grade_id}</td>
                        <td contenteditable="true" class="edit-lesson-id">${grade.lesson_id}</td>
                        <td contenteditable="true" class="edit-student-id">${grade.student_id}</td>
                        <td contenteditable="true" class="edit-grade-value grade-cell ${gradeClass}">${grade.grade_value}</td>
                        <td>
                            <button class="save-btn save-btn-grades">Сохранить</button>
                            <button class="delete-btn delete-btn-grades">Удалить</button>
                        </td>
                    </tr>`;
                tableBodyGrades.append(row);
            });
        }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Ошибка загрузки оценок:", textStatus, errorThrown);
        });
    }

    if ($('#grades-table').length > 0) {
        loadGrades();
    }

    $('#add-grade-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const gradeValue = formData.find(f => f.name === 'grade_value')?.value;

        if (![3,4,5].includes(parseInt(gradeValue))) {
            alert("Оценка должна быть 3, 4 или 5");
            return;
        }

        const data = { action: 'create' };
        formData.forEach(field => data[field.name] = field.value);

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/grade_api.php', JSON.stringify(data), function () {
            $('#add-grade-form')[0].reset();
            loadGrades();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Ошибка добавления оценки:", textStatus, errorThrown);
        });
    });

    $(document).on('click', '.save-btn-grades', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const newGradeValue = row.find('.edit-grade-value').text();
        const cell = row.find('.edit-grade-value');

        const newClass = getColorClass(newGradeValue);
        cell.removeClass("green dark-yellow orange gray").addClass(newClass);

        const grade = {
            action: 'update',
            grade_id: id,
            lesson_id: row.find('.edit-lesson-id').text(),
            student_id: row.find('.edit-student-id').text(),
            grade_value: newGradeValue
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/grade_api.php', JSON.stringify(grade), function () {
            loadGrades(); 
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Ошибка обновления оценки:", textStatus, errorThrown);
        });
    });

    $(document).on('click', '.delete-btn-grades', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', grade_id: id };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/grade_api.php', JSON.stringify(data), function () {
            loadGrades();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Ошибка удаления оценки:", textStatus, errorThrown);
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
    const tableBodyWorkload = $('#workload-table tbody');
    const sortGroupSelectWorkload = $('#sort-group-select');

    let currentWorkloadGroupFilter = '';

    function loadWorkloads() {
        let url = '/UP-08-Puchnin-Kriulin/controllers/api/workload_api.php';
        let params = {};

        if (currentWorkloadGroupFilter) {
            params.group_id = currentWorkloadGroupFilter;
        }

        $.get(url, params, function (data) {
            renderWorkloads(data);
        }, 'json');
    }

    function renderWorkloads(data) {
    console.log('Полученные данные:', data); // Логируем данные
    tableBodyWorkload.empty();

    if (!Array.isArray(data) || data.length === 0) {
        tableBodyWorkload.append('<tr><td colspan="10">Нет данных</td></tr>');
        return;
    }

    data.forEach(item => {
        const row = `
    <tr data-id="${item.workload_id}">
        <td>${item.workload_id}</td>
        <td contenteditable="true" class="edit-teacher-name">
            ${item.last_name} ${item.first_name} ${item.middle_name || ''}
        </td>
        <td contenteditable="true" class="edit-discipline-name">
            ${item.discipline_name || item.discipline_id}
        </td>
        <td contenteditable="true" class="edit-group-name">
            ${item.group_name || item.group_id}
        </td>
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
        tableBodyWorkload.append(row);
    });
}

    // --- Фильтр по группе ---
    if (sortGroupSelectWorkload.length > 0) {
        sortGroupSelectWorkload.on('change', function () {
            currentWorkloadGroupFilter = $(this).val().trim();
            loadWorkloads();
        });
    }

    // --- Сброс фильтров ---
    $('#reset-workload-filters').on('click', function () {
        currentWorkloadGroupFilter = '';
        sortGroupSelectWorkload.val('');
        loadWorkloads();
    });

    // --- Подгрузка групп ---
    function loadWorkloadGroups() {
        const groupSelect = $('#sort-group-select');
        if (groupSelect.length === 0) return;

        $.get('/UP-08-Puchnin-Kriulin/controllers/api/group_api.php', function (data) {
            groupSelect.empty().append('<option value="">Все группы</option>');

            data.forEach(group => {
                groupSelect.append(`<option value="${group.group_id}">${group.group_name}</option>`);
            });
        }, 'json');
    }

    // --- Инициализация при загрузке ---
    if ($('#workload-table').length > 0) {
        loadWorkloads();
        loadWorkloadGroups();
    }

    // --- Добавление новой нагрузки ---
    $('#add-workload-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        const data = { action: 'create' };

        formData.forEach(field => {
            data[field.name] = field.value;
        });

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/workload_api.php', JSON.stringify(data))
            .done(function () {
                $('#add-workload-form')[0].reset();
                loadWorkloads();
            })
            .fail(function (xhr) {
                alert("Ошибка добавления нагрузки");
            });
    });

    // --- Сохранение изменений ---
    $(document).on('click', '.save-btn-workload', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        const item = {
            action: 'update',
            workload_id: id,
            teacher_id: row.find('.edit-teacher-name').data('teacher-id') || row.find('.edit-teacher-name').text(),
            discipline_id: row.find('.edit-discipline-name').data('discipline-id') || row.find('.edit-discipline-name').text(),
            group_id: row.find('.edit-group-name').data('group-id'),
            lecture_hours: row.find('.edit-lecture-hours').text(),
            practice_hours: row.find('.edit-practice-hours').text(),
            consultation_hours: row.find('.edit-consultation-hours').text(),
            course_project_hours: row.find('.edit-course-project-hours').text(),
            exam_hours: row.find('.edit-exam-hours').text()
        };

        $.post('/UP-08-Puchnin-Kriulin/controllers/api/workload_api.php', JSON.stringify(item))
            .done(function () {
                loadWorkloads();
            })
            .fail(function (xhr) {
                alert("Ошибка сохранения");
            });
    });

    // --- Удаление записи ---
    $(document).on('click', '.delete-btn-workload', function () {
        const id = $(this).closest('tr').data('id');
        const data = { action: 'delete', workload_id: id };
        $.post('/UP-08-Puchnin-Kriulin/controllers/api/workload_api.php', JSON.stringify(data))
            .done(function () {
                loadWorkloads();
            })
            .fail(function (xhr) {
                alert("Ошибка удаления");
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