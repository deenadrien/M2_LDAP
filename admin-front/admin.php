<?php
    include_once '../ldap.php';
    $ldap = "ldap";
    $basedn = "dc=declercq,dc=teub";

    $connection = open($ldap);

    session_start();
    if($_SESSION['secure'] != true){
        header('Location: ../index.php');
    }
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
        <div class="text-right" style="padding:2%;">
            <a href="../process_deconnexion.php"><button class="btn btn-warning text-right">Déconnexion</button></a>
        </div>
        <center><h1>Accueil administration</h1></center>
        <fieldset>
            <div class="col-lg-8 offset-2" style="margin-bottom: 20px !important">
                    <legend>Gestion des utilisateurs</legend>
                    <center>
                        <button class="btn btn-success btn-sm" role="button" id="onShowUser">AJOUTER</button>
                        <a href="../process_delete_all_users.php"><button class="btn btn-danger btn-sm" role="button">SUPPRIMER TOUT</button></a>
                        <a href="../process_export_all_users.php"><button class="btn btn-warning btn-sm" role="button">EXPORT</button></a>
                        <button class="btn btn-info btn-sm" role="button" id="importFileUsers">IMPORT</button>
                    </center>
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
                <center>
                    <button class="btn btn-success btn-sm" role="button" id="onShowGroup">AJOUTER</button>
                    <a href="../process_delete_all_groups.php"><button class="btn btn-danger btn-sm" role="button">SUPPRIMER TOUT</button></a>
                    <a href="../process_export_all_groups.php"><button class="btn btn-warning btn-sm" role="button">EXPORT</button></a>
                    <button class="btn btn-info btn-sm" id="importFileGroups" role="button">IMPORT</button>
                </center>
            </div>
            <div class="col-lg-8 offset-2">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">#GID</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Description</th>
                        <th scope="col">Utilisateurs membres</th>
                        <th scope="col">Utilisateurs non membres</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $groups = findAllGroups($connection, $basedn);

                    foreach ($groups as $group) {
                        $description = isset($group['description']) ? $group['description'][0] : "";
                        if($group['cn'][0]){

                            $members = findUsersOfGroup($connection,$basedn,$group['cn'][0]);
                            $notmembers = findUsersNotOfGroup($connection,$basedn,$group['cn'][0]);

                            echo '
                                    <tr>
                                            <form action="../process_delete_user_to_group.php" method="post">
                                                <td>' . $group['gidnumber'][0] . '</td>
                                                <td>' . $group['cn'][0] . '<input type="hidden" value="' . $group['cn'][0] .'" name="cn"></td>
                                                <td>' . $description . '</td>
                                                <td>
                                                    <select name="uid">
                                                        <option value="">Choisir...</option>';
                                                        $i = 0;
                                                        foreach($members[0]["memberuid"] as $member){
                                                            if ($i) {
                                                                echo '<option value="' . $member . '">' . $member . '</option>';
                                                            }
                                                            $i++;
                                                        }
                                                echo '</select>
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                            </td>
                                            <td>
                                                <form action="../process_add_user_to_group.php" method="post">
                                                <input type="hidden" value="' . $group['cn'][0] .'" name="cn">
                                                <select name="uid">
                                                    <option value="">Choisir...</option>';
                                                  foreach($notmembers as $notmember){
                                                      if ($notmember['uid'][0]) {
                                                          echo '<option value="' . $notmember['uid'][0] . '">' . $notmember['uid'][0] . '</option>';
                                                      }
                                                  }
                                                echo '</select>
                                                <button type="submit" class="btn btn-success">Ajouter</button></form>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-default onUpdateGroup" data-gid="' . $group['gidnumber'][0] . '" role="button" ><img src="../img/edit.png" height="30px"></button>

                                                <a href="../process_delete_group.php?gidnumber=' . $group['gidnumber'][0] .'"><img src="../img/delete.png" height="30px"></a>
                                            </td>
                                          </tr>';

                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </fieldset>
        <div class="modal" tabindex="-1" role="dialog" id="importGroupModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Importer un fichier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="../process_import_group.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <label class="sr-only">Fichier</label>
                            <input type="file" name="groups" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Télécharger</E></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="exportModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Importer un fichier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="../process_import_user.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <label class="sr-only">Fichier</label>
                            <input type="file" name="users" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Télécharger</E></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
        <div class="modal" tabindex="-1" role="dialog" id="groupForm">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un groupe</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label class="sr-only">Nom</label>
                        <input type="text" id="titleGroup" class="form-control" placeholder="Titre." required autofocus>
                        <label class="sr-only">Nom</label>
                        <input type="text" id="descriptionGroup" class="form-control" placeholder="Desc." required autofocus>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="saveGroup">Enregistrer</E></button>
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
        <div class="modal" tabindex="-1" role="dialog" id="modifyGroup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier un groupe</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="gidGroup" class="form-control" placeholder="Desc." required autofocus readonly>
                        <input type="text" id="cnGroup" class="form-control" placeholder="CN" required autofocus readonly>
                        <label class="sr-only">Nom</label>
                        <input type="text" id="descriptionGroup" class="form-control" placeholder="Desc." required autofocus>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="updateGroup">Enregistrer</E></button>
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

        $('#onShowGroup').click(function () {
            $('#groupForm').modal('show');
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

        $('#saveGroup').click(function () {
            $.post("../process_add_group.php", {
                data: JSON.stringify({
                    gid: $('#inputGidGroup').val(),
                    title: $('#titleGroup').val(),
                    description: $('#descriptionGroup').val()
                })
            }).done(function (data) {
                if (data.success) {
                    $('.modal').find('input').val('');
                    location.reload();
                }
            });
        });

        $('.onUpdateGroup').click(function () {

            $.post("process_get_group.php", {
                data: JSON.stringify({
                    gidNumber: $(this).data('gid')
                })
            }).done(function (data) {
                if (data.group) {
                    var modal = $('#modifyGroup');

                    modal.find('#cnGroup').val(data.group['0'].cn['0']);
                    modal.find('#gidGroup').val(data.group['0'].gidnumber['0']);
                    modal.find('#descriptionGroup').val(data.group['0'].description['0']);
                }
            });

            $('#modifyGroup').modal('show');
        });

        $('#updateGroup').click(function () {
            var modal = $('#modifyGroup');

            $.post("../process_update_group.php", {
                data: JSON.stringify({
                    gid: modal.find('#gidGroup').val(),
                    cn: modal.find('#cnGroup').val(),
                    description: modal.find('#descriptionGroup').val()
                })
            }).done(function (data) {
                if (data.success) {
                    modal.find('input').val('');
                    location.reload();
                }
            });
        });

        $('#importFileUsers').click(function() {
            $('#exportModal').modal('show');
        });

        $('#importFileGroups').click(function() {
            $('#importGroupModal').modal('show');
        });
    });



</script>
</html>