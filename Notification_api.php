<?php
header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleNotificationGet($pdo);
        break;
    case 'POST':
        handleNotificationPost($pdo, $input);
        break;
    case 'PUT':
        handleNotificationPut($pdo, $input);
        break;
    case 'DELETE':
        handleNotificationDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleNotificationGet($pdo) {
    $userID = $_GET['UserID'] ?? null;
    if ($userID) {
        $sql = "SELECT * FROM Notification WHERE UserID = :UserID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['UserID' => $userID]);
    } else {
        $sql = "SELECT * FROM Notification";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function handleNotificationPost($pdo, $input) {
    $sql = "INSERT INTO Notification (UserID, Message, IsRead) VALUES (:UserID, :Message, :IsRead)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'UserID' => $input['UserID'],
        'Message' => $input['Message'],
        'IsRead' => $input['IsRead'] ?? false
    ]);
    echo json_encode(['message' => 'Notification created successfully']);
}

function handleNotificationPut($pdo, $input) {
    $sql = "UPDATE Notification SET IsRead = :IsRead WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'IsRead' => $input['IsRead'],
        'ID' => $input['ID']
    ]);
    echo json_encode(['message' => 'Notification updated successfully']);
}

function handleNotificationDelete($pdo, $input) {
    $sql = "DELETE FROM Notification WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ID' => $input['ID']]);
    echo json_encode(['message' => 'Notification deleted successfully']);
}
?>
