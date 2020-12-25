<?php
require_once "header.php";
$con = new connectionmodel();
$con->getconnection();
$result = $con->getAllUsers();

if (!$con->checkAdmin()) {
    echo '
    <div class="alert alert-danger" role="alert">
      YOU ARE NOT AUTHORIZED TO BE HERE
    </div>
    <a href="./main.php" class="btn btn-primary m-2">Go back to homepage</a>
    ';
} else {
?>
    <a href="./main.php" class="btn btn-primary m-2">Go back to homepage</a>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Control</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($result as $row) {?>
            <tr>
                <th scope="row"><?php echo $row[0] ?></th>
                <td><?php echo $row[1] ?></td>
                <td><?php echo (isset($_POST['showpass'])) ? $con->showPassword($row[3]) : $row[2] ?></td>
                <td>
                    <button type="button" class="btn btn-primary" name="showpass" data-bs-toggle="modal" data-bs-target="<?php echo "#showpass". $row[0] ?>">Show</button>
                </td>
            </tr>
            <div class="modal fade" id="<?php echo "showpass" . $row[0] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><?php echo $row[1] ?> Site Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php echo $con->showPassword($row[2]) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        </tbody>
    </table>

<?php
}
require_once "footer.php" ?>