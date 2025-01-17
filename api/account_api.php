<?php
header("Content-Type: application/json");
include 'db.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        if (isset($_GET['action']) && $_GET['action'] === 'register') {
            handleRegister($pdo, $input); 
        } else {
            handlePost($pdo, $input);
        }
        break;
    case 'PUT':
        handlePut($pdo, $input);
        break;
    case 'DELETE':
        handleDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}


function handleGet($pdo) {
    $sql = "SELECT * FROM Account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}


function handlePost($pdo, $input) {
    $sql = "INSERT INTO Account (UserName, Email, Password, Role, Reputation) 
            VALUES (:UserName, :Email, :Password, :Role, :Reputation)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'UserName' => $input['UserName'],
        'Email' => $input['Email'],
        'Password' => password_hash($input['Password'], PASSWORD_BCRYPT),
        'Role' => $input['Role'] ?? 'User',
        'Reputation' => $input['Reputation'] ?? 0
    ]);
    echo json_encode(['message' => 'Account created successfully']);
}


function handleRegister($pdo, $input) {
    if (empty($input['UserName']) || empty($input['Email']) || empty($input['Password'])) {
        echo json_encode(['message' => 'All fields are required']);
        return;
    }

    
    $sql = "SELECT * FROM Account WHERE Email = :Email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['Email' => $input['Email']]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo json_encode(['message' => 'Email already exists']);
        return;
    }

    
    $sql = "INSERT INTO Account (UserName, Email, Password, Role, Reputation) 
            VALUES (:UserName, :Email, :Password, :Role, :Reputation)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'UserName' => $input['UserName'],
        'Email' => $input['Email'],
        'Password' => password_hash($input['Password'], PASSWORD_BCRYPT),
        'Role' => 'User',
        'Reputation' => 0
    ]);

    echo json_encode(['message' => 'Registration successful']);
}


function handlePut($pdo, $input) {
    $sql = "UPDATE Account 
            SET UserName = :UserName, Email = :Email, Password = :Password, Role = :Role, Reputation = :Reputation 
            WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'UserName' => $input['UserName'],
        'Email' => $input['Email'],
        'Password' => password_hash($input['Password'], PASSWORD_BCRYPT),
        'Role' => $input['Role'],
        'Reputation' => $input['Reputation'],
        'ID' => $input['ID']
    ]);
    echo json_encode(['message' => 'Account updated successfully']);
}


function handleDelete($pdo, $input) {
    $sql = "DELETE FROM Account WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ID' => $input['ID']]);
    echo json_encode(['message' => 'Account deleted successfully']);
}
?>
