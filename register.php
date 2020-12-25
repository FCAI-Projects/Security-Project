<?php
require_once "header.php";
if (isset($_SESSION['id'])) header("location: main.php");


if (isset($_POST['register'])) {
    $con = new connectionmodel();
    $con->getconnection();
    $res = $con->registeration($_POST['username'], $_POST['password']);
    $con->closecon();
    if ($res && isset($_SESSION['id'])) header("location: main.php");
    else {
        echo "<script>alert('Username or Password Incorrect')</script>";
    }
}

?>
<div class="d-flex justify-content-center login">
    <form class="text-center" method="post">
        <label class="font-weight-bold label">Resgister</label>
        <input type="username" name="username" placeholder="Username" class="form-control  id" id="exampleInputEmail1"
               aria-describedby="emailHelp" autocomplete="off">
        <input type="password" name="password" placeholder="Password" class="form-control" id="exampleInputPassword1">
        <input type="submit" class="btn btn-primary" name="register" value="submit"/>
        <a href="./index.php">Login</a>
    </form>
</div>

<?php require_once "footer.php"?>