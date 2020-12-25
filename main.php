<?php
require_once "header.php";

if (!isset($_SESSION['id'])) {
    header("location: index.php");
}


$con = new connectionmodel();

if (isset($_POST['add'])) {
    $con->getconnection();
    $res = $con->add_site($_POST['site'], $_POST['pass']);

    $con->closecon();
}

if (isset($_POST['edit'])) {
    $con->getconnection();
    $con->editSite($_POST["id"], $_POST["site"], $_POST["pass"]);
    $con->closecon();
}

if (isset($_POST['delete'])) {
    $con->getconnection();
    $ress = $con->deleteSite($_POST['id']);
    $con->closecon();
}

if (isset($_POST['showpass'])) {

}

$con->getconnection();
$result = $con->get_table();
?>

<?php
if (isset($res) && $res == 1) {
    echo '
    <div class="alert alert-success" role="alert">
      Site added
    </div>
    ';
} else if (isset($res) && $res == 0) {
    echo '
    <div class="alert alert-danger" role="alert">
      Can\'t add site 
    </div>
    ';
}

if (isset($ress) && $ress == 1) {
    echo '
    <div class="alert alert-success" role="alert">
      Site deleted
    </div>
    ';
} else if (isset($ress) && $ress == 0) {
    echo '
    <div class="alert alert-danger" role="alert">
      Can\'t delete site 
    </div>
    ';
}

?>

<form action="./index.php" method="post" class="main">
    <input type="submit" name="logout" class="btn btn-danger" value="Logout"/>
    <button type="button" class="btn btn-primary float-end m-2" data-bs-toggle="modal" data-bs-target="#add">Add</button>
    <?php if ($con->checkAdmin()): ?>
        <a href="./dashboard.php" class="btn btn-warning float-end m-2">Admin Dashboard</a>
    <?php endif; ?>
</form>
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Site</th>
        <th scope="col">Password</th>
        <th scope="col">Control</th>
    </tr>
    </thead>
    <tbody>

        <?php foreach ($result as $row) {?>
        <tr>
            <th scope="row"><?php echo $row[0] ?></th>
            <td><?php echo $row[2] ?></td>
            <td><?php echo (isset($_POST['showpass'])) ? $con->showPassword($row[3]) : $row[3] ?></td>
            <td>
                <form method="post">
                    <button type="button" class="btn btn-primary" name="showpass" data-bs-toggle="modal" data-bs-target="<?php echo "#showpass". $row[0] ?>">Show</button>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="<?php echo "#edit". $row[0] ?>">Edit</button>
                    <input type="hidden" name="id" value="<?php echo $row[0] ?>"/>
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <div class="modal fade" id="<?php echo "edit" . $row[0] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Site</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $row[0] ?>"/>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Site Name</label>
                                <input name="site" type="text" class="form-control" id="exampleInputEmail1"
                                       aria-describedby="emailHelp" value="<?php echo $row[2] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input name="pass" type="text" class="form-control" id="exampleInputPassword1" value="<?php echo $con->showPassword($row[3]) ?>">
                            </div>
                            <button name="edit" type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="<?php echo "showpass" . $row[0] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $row[2] ?> Site Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo $con->showPassword($row[3]) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </tbody>
</table>




<div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Site</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Site Name</label>
                        <input name="site" type="text" class="form-control" id="exampleInputEmail1"
                               aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input name="pass" type="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <button name="add" type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>



<?php require_once "footer.php"?>