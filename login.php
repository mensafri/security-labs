<?php
// login.php
include 'db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header("Location: profile.php");
    } else {
        echo "Login gagal!";
    }
}
?>

<h2>Login User</h2>
<form method="POST">
    Username: <input type="text" name="username" value="admin"><br>
    Password: <input type="password" name="password" value="12345"><br>
    <button type="submit" name="login">Login</button>
</form>