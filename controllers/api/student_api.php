<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Student.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = "
            SELECT s.*, g.group_name 
            FROM Students s
            LEFT JOIN StudentGroups g ON s.group_id = g.group_id
            WHERE 1=1
        ";

        if (!empty($_GET['search'])) {
            $search = mysqli_real_escape_string($db_connection, $_GET['search']);
            $query .= " AND (
                s.last_name LIKE '%$search%' OR
                s.first_name LIKE '%$search%' OR
                s.middle_name LIKE '%$search%'
            )";
        }

        if (!empty($_GET['group_id'])) {
            $groupId = mysqli_real_escape_string($db_connection, $_GET['group_id']);
            $query .= " AND s.group_id = '$groupId'";
        }

        if (!empty($_GET['dismissal'])) {
            if ($_GET['dismissal'] === 'not_null') {
                $query .= " AND s.dismissal_date IS NOT NULL";
            } elseif ($_GET['dismissal'] === 'null') {
                $query .= " AND s.dismissal_date IS NULL";
            }
        }

        $result = mysqli_query($db_connection, $query);
        $items = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = new Student((object)$row);
        }

        echo json_encode($items);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $student = new Student();
                    foreach ($data as $key => $value) {
                        if (property_exists($student, $key)) {
                            $student->{$key} = $value;
                        }
                    }

                    $errors = $student->validate();
                    if (!empty($errors)) {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'errors' => $errors]);
                        exit();
                    }

                    $student->Insert();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'update':
                    $student = new Student();
                    $student->student_id = $data['student_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($student, $key)) {
                            $student->{$key} = $value;
                        }
                    }

                    $errors = $student->validate();
                    if (!empty($errors)) {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'errors' => $errors]);
                        exit();
                    }

                    $student->Update();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'delete':
                    $student = new Student();
                    $student->student_id = $data['student_id'];
                    $deleted = $student->Delete();

                    if ($deleted) {
                        echo json_encode(['status' => 'success']);
                    } else {
                        http_response_code(400);
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Нельзя удалить студента — он связан с пропусками или оценками'
                        ]);
                    }
                    break;
            }
            exit();
        }
    }
?>