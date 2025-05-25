<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/DisciplineProgram.php";

    require_once "log_error.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = "
            SELECT 
                dp.discipline_id,
                g.group_name,
                COUNT(*) AS lesson_count,
                SUM(dp.hours) AS total_hours
            FROM Discipline_Programs dp
            LEFT JOIN StudentGroups g ON dp.group_id = g.group_id
            WHERE dp.discipline_id = $disciplineId
            GROUP BY dp.group_id
        ";

        if (!empty($_GET['discipline_id'])) {
            $disciplineId = mysqli_real_escape_string($db_connection, $_GET['discipline_id']);
            $query .= " WHERE dp.discipline_id = $disciplineId ";
        }

        $query .= "GROUP BY tw.group_id";

        $result = mysqli_query($db_connection, $query);
        $items = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = (object)$row;
        }

        echo json_encode($items);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once "log_error.php";
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $item = new DisciplineProgram();
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Insert();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'update':
                    $item = new DisciplineProgram();
                    $item->program_id = $data['program_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Update();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'delete':
                    $item = new DisciplineProgram();
                    $item->program_id = $data['program_id'];
                    $item->Delete();
                    echo json_encode(['status' => 'success']);
                    break;
            }
            exit();
        }
    }
    logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса']);
    exit();
?>