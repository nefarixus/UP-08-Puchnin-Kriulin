<?php
    require_once "./includes/parts/connection.php";
    include_once "./includes/parts/header.php";
?>
    <main>
        <div class="cards-container">
            <div class="card">
                <p class="h2">Студенты</p>
                <a href="./pages/students.php">Перейти</a>
            </div>
            <div class="card">
                <p class="h2">Дисциплины</p>
                <a href="./pages/disciplines.php">Перейти</a>
            </div>
            <div class="card">
                <p class="h2">Пропуски занятий</p>
                <a href="./pages/absences.php">Перейти</a>
            </div>
            <div class="card">
                <p class="h2">Учебные группы</p>
                <a href="./pages/student_groups.php">Перейти</a>
            </div>
            <div class="card">
                <p class="h2">Программа</br>дисциплины</p>
                <a href="./pages/disciplines_programs.php">Перейти</a>
            </div>
            <div class="card">
                <p class="h2">Преподаватели</p>
                <a href="./pages/teachers.php">Перейти</a>
            </div>
            <div class="card">
                <p class="h2">Оценки</p>
                <a href="./pages/grades.php">Перейти</a>
            </div>
            <div class="card">
                <p class="h2">Преподавательская нагрузка</p>
                <a href="./pages/workload.php">Перейти</a>
            </div>
            <div class="card">
                <p class="h2">Консультации</p>
                <a href="./pages/consultations.php">Перейти</a>
            </div>
            <div class="card">
                <p class="h2">Занятия</p>
                <a href="./pages/lessons.php">Перейти</a>
            </div>
        </div>
</main>
<?php
    include_once "./includes/parts/footer.php";
?>