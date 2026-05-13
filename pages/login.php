<?php
session_start();
require_once "../db_config.php";
require_once "../services/UserService.php";

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $userService = new UserService($connection);
    $user = $userService->login($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: ../index.php');
        exit;
    } else {
        $error = 'Hibás felhasználónév vagy jelszó.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="../styles/auth.css">
</head>

<body>
    <div class="form-container">
        <h2>Bejelentkezés</h2>

        <?php
        if ($error) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>

        <form method="POST">
            <div class="input">
                <label for="username">Felhasználónév</label>
                <input type="text" name="username">
            </div>

            <div class="input">
                <label for="password">Jelszó</label>
                <input type="password" name="password">
            </div>

            <button type="submit">Bejelentkezés</button>
        </form>
        <p>Még nincs fiókod? <a href="register.php">Regisztrálj</a></p>
    </div>
</body>

</html>