<?php
header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handlePostGet($pdo);
        break;
    case 'POST':
        handlePostPost($pdo, $input);
        break;
    case 'PUT':
        handlePostPut($pdo, $input);
        break;
    case 'DELETE':
        handlePostDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handlePostGet($pdo) {
    $postID = $_GET['ID'] ?? null;
    if ($postID) {
        $sql = "SELECT * FROM Post WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['ID' => $postID]);
    } else {
        $sql = "SELECT * FROM Post";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function handlePostPost($pdo, $input) {
    $sql = "INSERT INTO Post (UserID, Title, Description, Upvotes, Downvotes) 
            VALUES (:UserID, :Title, :Description, :Upvotes, :Downvotes)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'UserID' => $input['UserID'],
        'Title' => $input['Title'],
        'Description' => $input['Description'],
        'Upvotes' => $input['Upvotes'] ?? 0,
        'Downvotes' => $input['Downvotes'] ?? 0
    ]);
    echo json_encode(['message' => 'Post created successfully']);
}

function handlePostPut($pdo, $input) {
    $sql = "UPDATE Post 
            SET Title = :Title, Description = :Description, Upvotes = :Upvotes, Downvotes = :Downvotes 
            WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'Title' => $input['Title'],
        'Description' => $input['Description'],
        'Upvotes' => $input['Upvotes'],
        'Downvotes' => $input['Downvotes'],
        'ID' => $input['ID']
    ]);
    echo json_encode(['message' => 'Post updated successfully']);
}

function handlePostDelete($pdo, $input) {
    $sql = "DELETE FROM Post WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ID' => $input['ID']]);
    echo json_encode(['message' => 'Post deleted successfully']);
}
?>
