<?php
require_once "header.php";


if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
}

@session_start();

if (isset($_SESSION['id'])) header("location: main.php");
if (isset($_POST['login'])) {
    $con = new connectionmodel();
    $con->getconnection();
    $res = $con->checkuser($_POST['username'], $_POST['pass']);
    $con->closecon();
    if ($res && isset($_SESSION['id'])) header("location: main.php");
    else {
        echo "<script>alert('Username or Password Incorrect')</script>";
    }
}


?>

<div class="d-flex justify-content-center login">
    <form method="post" class="text-center">
        <label class="font-weight-bold label">Login</label>
        <input name="username" type="username" placeholder="Username" class="form-control  id" id="exampleInputEmail1"
               aria-describedby="emailHelp">
        <input name="pass" type="password" placeholder="Password" class="form-control password"
               id="exampleInputPassword1">
        <input type="submit" class="btn btn-primary" name="login" value="login"/>
        <a href="./register.php">Resgister</a>
    </form>
</div>

<?php require_once "footer.php"?>