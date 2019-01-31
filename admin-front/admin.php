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

    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">

</head>
<body>
    <div id="list-users">
        <center><h1>Accueil administration</h1></center>
        <fieldset>
            <div class="col-lg-8 offset-2" style="margin-bottom: 20px !important">
                    <legend>Gestion des utilisateurs</legend>
                    <center><button class="btn btn-success btn-sm" role="button" id="onShowUser">AJOUTER</button></center>
            </div>
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
                                $users = findAllUsers($connection, $basedn);

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
                                                <button class="btn btn-sm btn-default onUpdateUser" data-uid="' . $user['uid'][0] . '" role="button" ><img src="../img/edit.png" height="30px"></button>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="../process_delete_user.php?uid=' . $user['uid'][0] .'"><img src="../img/delete.png" height="30px"></a>
                                            </td>
                                          </tr>';
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
        </fieldset>
        <fieldset>
            <div class="col-lg-8 offset-2" style="margin-bottom: 20px !important">
                <legend>Gestion des groupes</legend>
                <center><button class="btn btn-success btn-sm" role="button" id="onShowUser">AJOUTER</button></center>
            </div>
            <div class="col-lg-8 offset-2">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">#UID</th>
                        <th scope="col">SN</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $users = findAllGroups($connection, $basedn);

                    foreach ($users as $user) {
                        if($user['ou'][0]){
                            echo '<tr>
                                            <td>' . $user['dn'][0] . '</td>
                                            <td>' . $user['ou'][0] . '</td>
                                            <td>
                                                <a href="#"><img src="../img/edit.png" height="30px"></a>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="../process_delete_user.php?uid=' . $user['ou'][0] .'"><img src="../img/delete.png" height="30px"></a>
                                            </td>
                                          </tr>';
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </fieldset>
        <div class="modal" tabindex="-1" role="dialog" id="userForm">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label class="sr-only">Login</label>
                        <input type="text" id="inputUserLogin" class="form-control" placeholder="Login" required autofocus>

                        <label class="sr-only">Nom</label>
                        <input type="text" id="inputUserName" class="form-control" placeholder="Nom" required autofocus>

                        <label class="sr-only">Prénom</label>
                        <input type="text" id="inputUserFirstname" class="form-control" placeholder="Prénom" required autofocus>

                        <label class="sr-only">Mot de passe</label>
                        <input type="password" id="inputUserPassword" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="save">Enregistrer</E></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="modifyUser">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier un utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label>Login</label>
                        <input type="text" id="login" class="form-control" placeholder="Login" required readonly>

                        <label>SN</label>
                        <input type="text" id="sn" class="form-control" placeholder="SN" required autofocus>

                        <label>GivenName</label>
                        <input type="text" id="givenName" class="form-control" placeholder="Given Name" required autofocus>

                        <label">HomeDirectory</label>
                        <input type="text" id="homeDirectory" class="form-control" placeholder="Home Directory" required autofocus>

                        <label>Password</label>
                        <input type="password" id="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="update">Enregistrer</E></button>
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

        $('.onUpdateUser').click(function () {

            $.post("process_get_user.php", {
                data: JSON.stringify({
                    uid: $(this).data('uid')
                })
            }).done(function (data) {
                if (data.user) {
                    var modal = $('#modifyUser');

                    modal.find('#login').val(data.user['0'].uid['0']);
                    modal.find('#sn').val(data.user['0'].sn['0']);
                    modal.find('#givenName').val(data.user['0'].givenname['0']);
                    modal.find('#homeDirectory').val(data.user['0'].homedirectory['0']);
                }
            });

            $('#modifyUser').modal('show');
        });

        $('#save').click(function () {
            $.post("../process_add_user.php", {
                data: JSON.stringify({
                    name: $('#inputUserName').val(),
                    firstname: $('#inputUserFirstname').val(),
                    login: $('#inputUserLogin').val(),
                    password: $('#inputUserPassword').val()
                })
            }).done(function (data) {
                if (data.success) {
                    $('.modal').find('input').val('');
                    location.reload();
                }
            });
        });

        $('#update').click(function () {
            var modal = $('#modifyUser');

            $.post("../process_update_user.php", {
                data: JSON.stringify({
                    uid: modal.find('#login').val(),
                    sn: modal.find('#sn').val(),
                    givenName: modal.find('#givenName').val(),
                    homeDirectory: modal.find('#homeDirectory').val(),
                    password: modal.find('#password').val()
                })
            }).done(function (data) {
                if (data.success) {
                    modal.find('input').val('');
                    location.reload();
                }
            });
        });
    });



</script>
</html>