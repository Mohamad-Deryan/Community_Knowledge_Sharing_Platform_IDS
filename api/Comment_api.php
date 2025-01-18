<?php
header("Content-Type: application/json");
include 'db.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleCommentGet($pdo);
        break;
    case 'POST':
        handleCommentPost($pdo, $input);
        break;
    case 'PUT':
        handleCommentPut($pdo, $input);
        break;
    case 'DELETE':
        handleCommentDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleCommentGet($pdo) {
    $postID = $_GET['PostID'] ?? null;
    if ($postID) {
        $sql = "SELECT * FROM Comment WHERE PostID = :PostID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['PostID' => $postID]);
    } else {
        $sql = "SELECT * FROM Comment";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function handleCommentPost($pdo, $input) {
    $sql = "INSERT INTO Comment (PostID, UserID, Content) VALUES (:PostID, :UserID, :Content)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'PostID' => $input['PostID'],
        'UserID' => $input['UserID'],
        'Content' => $input['Content']
    ]);
    echo json_encode(['message' => 'Comment created successfully']);
}

function handleCommentPut($pdo, $input) {
    $sql = "UPDATE Comment SET Content = :Content WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'Content' => $input['Content'],
        'ID' => $input['ID']
    ]);
    echo json_encode(['message' => 'Comment updated successfully']);
}

function handleCommentDelete($pdo, $input) {
    $sql = "DELETE FROM Comment WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ID' => $input['ID']]);
    echo json_encode(['message' => 'Comment deleted successfully']);
}
?>
