<?php
header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleCategoryGet($pdo);
        break;
    case 'POST':
        handleCategoryPost($pdo, $input);
        break;
    case 'DELETE':
        handleCategoryDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleCategoryGet($pdo) {
    $sql = "SELECT * FROM Category";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function handleCategoryPost($pdo, $input) {
    $sql = "INSERT INTO Category (Name) VALUES (:Name)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['Name' => $input['Name']]);
    echo json_encode(['message' => 'Category created successfully']);
}

function handleCategoryDelete($pdo, $input) {
    $sql = "DELETE FROM Category WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ID' => $input['ID']]);
    echo json_encode(['message' => 'Category deleted successfully']);
}
?>
