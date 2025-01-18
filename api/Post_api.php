<?php
header("Content-Type: application/json");
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Session user_id is not set']);
    exit;
}

include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'categories') {
            handleGetCategories($pdo);
        } else {
            handlePostGet($pdo);
        }
        break;
    case 'POST':
        handlePostPost($pdo);
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

function handleGetCategories($pdo) {
    $sql = "SELECT * FROM Category";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function handlePostGet($pdo) {
    $search = $_GET['search'] ?? '';
    $postId = $_GET['id'] ?? null;

    $imagePath = 'http://localhost/IDS%20Community%20Sharing%20Platform/api/uploads/';

    if ($postId) {
        $sql = "SELECT Post.*, CONCAT('$imagePath', Post.ImageURL) AS ImageURL, Account.UserName 
                FROM Post 
                JOIN Account ON Post.UserID = Account.ID 
                WHERE Post.ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['ID' => $postId]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $sql = "SELECT Post.*, CONCAT('$imagePath', Post.ImageURL) AS ImageURL, Account.UserName 
                FROM Post 
                JOIN Account ON Post.UserID = Account.ID 
                WHERE Post.Title LIKE :search 
                OR Post.Description LIKE :search 
                ORDER BY Post.CreatedAt DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['search' => "%$search%"]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

function handlePostPost($pdo) {
    try {
        $userID = $_SESSION['user_id']; 
        if (empty($userID)) {
            echo json_encode(['error' => 'User ID is missing from the session.']);
            http_response_code(400);
            exit();
        }
        $title = $_POST['Title'];
        $description = $_POST['Description'];
        $categoryID = $_POST['CategoryID'];
        $imageURL = null;

        if (isset($_FILES['Image']) && $_FILES['Image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $fileName = uniqid() . '-' . basename($_FILES['Image']['name']);
            $targetFile = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['Image']['tmp_name'], $targetFile)) {
                throw new Exception('Failed to upload image.');
            }
            $imageURL = $fileName; 
        }

        $sql = "INSERT INTO Post (UserID, Title, Description, ImageURL, Upvotes, Downvotes) 
                VALUES (:UserID, :Title, :Description, :ImageURL, :Upvotes, :Downvotes)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'UserID' => $userID,
            'Title' => $title,
            'Description' => $description,
            'ImageURL' => $imageURL,
            'Upvotes' => 0,
            'Downvotes' => 0
        ]);

        $postID = $pdo->lastInsertId();

        $categoryStmt = $pdo->prepare("INSERT INTO PostCategory (PostID, CategoryID) VALUES (:PostID, :CategoryID)");
        $categoryStmt->execute([
            'PostID' => $postID,
            'CategoryID' => $categoryID,
        ]);

        echo json_encode(['message' => 'Post created successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
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
