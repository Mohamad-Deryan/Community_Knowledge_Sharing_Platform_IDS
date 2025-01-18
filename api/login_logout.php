<?php 
header("Content-Type: application/json");
session_start();
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'login') {
                handleLogin($pdo, $input);
            } elseif ($_GET['action'] === 'logout') {
                handleLogout();
            } else {
                invalidActionResponse();
            }
        } else {
            invalidActionResponse();
        }
        break;

    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'validate') {
            validateSession();
        } else {
            invalidActionResponse();
        }
        break;

    default:
        echo json_encode(['message' => 'Invalid request method']);
        http_response_code(405);
        break;
}

function handleLogin($pdo, $input) {
    
    if (empty($input['Email']) || empty($input['Password'])) {
        echo json_encode(['message' => 'Email and Password are required']);
        http_response_code(400);
        return;
    }

    
    $sql = "SELECT * FROM Account WHERE Email = :Email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['Email' => $input['Email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($user && password_verify($input['Password'], $user['Password'])) {
        
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['user_email'] = $user['Email'];
        $_SESSION['user_role'] = $user['Role'];
        
        echo json_encode([
            'message' => 'Login successful',
            'user' => [
                'ID' => $user['ID'],
                'UserName' => $user['UserName'],
                'Email' => $user['Email'],
                'Role' => $user['Role'],
                'Reputation' => $user['Reputation'] ?? 0, 
            ]
        ]);
    } else {
        echo json_encode(['message' => 'Invalid email or password']);
        http_response_code(401);
    }
}

function handleLogout() {
    session_unset();
    session_destroy();

    echo json_encode(['message' => 'Logout successful']);
}

function validateSession() {
    if (isset($_SESSION['user_id'])) {
        echo "Session is running. User ID: " . $_SESSION['user_id'];
        echo json_encode([
            'isLoggedIn' => true,
            'user' => [
                'ID' => $_SESSION['user_id'],
                'Email' => $_SESSION['user_email'],
                'Role' => $_SESSION['user_role']
            ]
        ]);
    } else {
        echo "No active session for user.";
        echo json_encode(['isLoggedIn' => false]);
    }
}

function invalidActionResponse() {
    echo json_encode(['message' => 'Invalid action']);
    http_response_code(400);
}
?>
