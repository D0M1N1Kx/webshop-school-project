<?php
session_start();
require_once "../db_config.php";
require_once "../services/UserService.php";

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Minden mező kitöltése kötelező.";
    } elseif (strlen($password) < 6) {
        $error = "A jelszó minimum 6 karakternek kell lennie.";
    } elseif ($password !== $confirm) {
        $error = "A két jelszó nem egyezik.";
    } else {
        $userService = new userService($connection);
        $result = $userService->register($username, $password);

        if ($result === 'exists') {
            $error = "Ez a felhasználónév már foglalt.";
        } elseif ($result === true) {
            $success = "Sikeres regisztráció! <a href='login.php'>Bejelentkezés</a>";
        } else {
            $error = "Ismeretlen hiba történt.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztrácó</title>
    <link rel="stylesheet" href="../styles/auth.css">
</head>

<body>
    <div class="form-container">
        <h2>Regisztráció</h2>

        <form method="POST">
            <label>Felhasználónév</label>
            <input type="text" name="username">

            <label>Jelszó</label>
            <input type="password" name="password">

            <label>Jelszó megerősítése</label>
            <input type="password" name="confirm_password">

            <button type="submit">Regisztráció</button>
        </form>
        <p>Már van fiókod? <a href="login.php">Jelentkezz be</a></p>
    </div>
</body>

</html>