<?php  
session_start(); 
$Title = 'Login' ;
include('header.php');

$username = 'admin';
$password = 'cs564admin';
$message = '';

if (isset($_POST['username']) && isset($_POST['password']))
{
    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        $_SESSION['login'] = true;
        header('LOCATION:admin.php');
        die();
    } else {
        $message = "<h4>Incorrect username or password</h4>";
    }
}

echo $message;
?>

<form name='input' action='login.php' method='post'>
    <label for='username'>Username</label><input type='text' id='username' placeholder='username' name='username' />
    <label for='password'>Password</label><input type='password' id='password' placeholder='password' name='password' />
    <input type='submit' value='Login' name='submit' />
</form>

</div>
</body>
</html>