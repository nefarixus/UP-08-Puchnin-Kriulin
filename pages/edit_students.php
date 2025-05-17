<?php
    require_once "../includes/parts/connection.php";
    include_once "../includes/parts/header.php";

    if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $query = "SELECT * FROM Students WHERE student_id = $id";
            $result = mysqli_query($db_connection, $query);
            if (!$result || mysqli_num_rows($result) == 0) {
                die("Студент не найден.");
            }

            $row = mysqli_fetch_assoc($result);

            if (isset($_POST['save'])) {
                $last_name = $_POST['last_name'];
                $first_name = $_POST['first_name'];
                $middle_name = $_POST['middle_name'];
                $group_id = $_POST['group_id'];
                $dismissal_date = $_POST['dismissal_date'];
                
                if (empty($dismissal_date)) {
                    $dismissal_date_sql = "NULL";
                } else {
                    $dismissal_date_sql = "'" . $dismissal_date . "'";
                }

                $update_query = "UPDATE Students SET 
                                last_name = '$last_name',
                                first_name = '$first_name',
                                middle_name = '$middle_name',
                                group_id = '$group_id',
                                dismissal_date = $dismissal_date_sql
                            WHERE student_id = $id";

                mysqli_query($db_connection, $update_query);
                header("Location: /EP-08/pages/students.php");
                exit();
            }
        }
?>
    <main>
        <h2>Редактировать данные о студенте</h2>
        <form method="post">
            <input type="text" name="last_name" value="<?= $row['last_name'] ?>"><br>
            <input type="text" name="first_name" value="<?= $row['first_name'] ?>"><br>
            <input type="text" name="middle_name" value="<?= $row['middle_name'] ?>"><br>
            <input type="int" name="group_id" value="<?= $row['group_id'] ?>"><br>
            <input type="date" name="dismissal_date" value="<?= $row['dismissal_date'] ?>"><br>
            <input type="submit" name="save" value="Сохранить">
        </form>
    </main>
<?php
    include_once "../includes/parts/footer.php";
?>