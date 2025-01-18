<?php
header("Content-Type: application/json");
include 'db.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        handlePostCategoryPost($pdo, $input);
        break;
    case 'DELETE':
        handlePostCategoryDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handlePostCategoryPost($pdo, $input) {
    $sql = "INSERT INTO PostCategory (PostID, CategoryID) VALUES (:PostID, :CategoryID)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'PostID' => $input['PostID'],
        'CategoryID' => $input['CategoryID']
    ]);
    echo json_encode(['message' => 'Category assigned to post successfully']);
}

function handlePostCategoryDelete($pdo, $input) {
    $sql = "DELETE FROM PostCategory WHERE PostID = :PostID AND CategoryID = :CategoryID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'PostID' => $input['PostID'],
        'CategoryID' => $input['CategoryID']
    ]);
    echo json_encode(['message' => 'Category removed from post successfully']);
}
?>
