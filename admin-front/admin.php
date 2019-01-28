<?php
    include_once '../ldap.php';
    $ldap = "ldap";
    $basedn = "dc=declercq,dc=teub";

    $connection = open($ldap);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">

</head>
<body>
    <div id="list-users">
        <center><h1>Accueil administration</h1></center>
        <button class="btn btn-success" role="button" id="onShowUser">AJOUTER</button>
        <div class="col-lg-8 offset-2"><h2>Gestion des utilisateurs</h2></div>
        <div class="col-lg-8 offset-2">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#UID</th>
                <th scope="col">SN</th>
                <th scope="col">GivenName</th>
                <th scope="col">CN</th>
                <th scope="col">UID</th>
                <th scope="col">Home Directory</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    $users = findAll($connection, $basedn);

                    foreach ($users as $user) {
                        if($user['uid'][0]){
                            echo '<tr>
                                <td>' . $user['uidnumber'][0] . '</td>
                                <td>' . $user['sn'][0] . '</td>
                                <td>' . $user['givenname'][0] . '</td>
                                <td>' . $user['cn'][0] . '</td>
                                <td>' . $user['uid'][0] . '</td>
                                <td>' . $user['homedirectory'][0] . '</td>
                                <td>
                                    <a href="#"><img src="../img/edit.png" height="30px"></a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="#"><img src="../img/delete.png" height="30px"></a>
                                </td>
                              </tr>';
                        }
                    }
                ?>
            </tbody>
        </table>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="userForm">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="application/javascript">
    $( document ).ready(function() {
        $('#onShowUser').click(function () {
            $('#userForm').modal('show');
        });
    });

</script>
</html>

<?php
echo '<pre>';
print_r($users);
echo '</pre>';
?>