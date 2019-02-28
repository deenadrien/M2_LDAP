<?php
    include_once '../ldap.php';

    session_start();
    if(!$_SESSION['secure']){
        header('Location: ../index.php');
    }

    $ldap = "ldap";
    $basedn = "dc=declercq,dc=teub";

    $connection = open($ldap);

    $uid = $_GET['uid'];

    $user = findOneUser($connection,$basedn,$uid);
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
        <center><img src="../img/user.png"></center>
        <center><h1>Accueil utilisateur</h1></center>
        <div class="container">

                <div class="form-group">
                    <label for="exampleFormControlInput1">Nom</label>
                    <?php echo '<input type="text" class="form-control" name="nom" id="exampleFormControlInput1" value="' . $user[0]["sn"][0] . '" readonly>'; ?>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Prénom</label>
                    <?php echo '<input type="text" class="form-control" name="prenom" id="exampleFormControlInput1" value="' . $user[0]["givenname"][0] . '" readonly>'; ?>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Login</label>
                    <?php echo '<input type="text" class="form-control" name="login" id="uid" value="' . $user[0]["uid"][0] . '" readonly>'; ?>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nouveau mot de passe</label>
                    <input type="password" class="form-control" name="pass" id="pass1">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Répeter le mot de passe</label>
                    <input type="password" class="form-control" name="pass2" id="pass2">
                </div>
                <center><button class="btn btn-success" type="button" id="update">Modifier</button></center>

        </div>
    </div>
</body>
<script type="application/javascript">
    $( document ).ready(function() {
        $('#update').click(function () {
            var pass1 = $('#pass1').val(),
                pass2 = $('#pass2').val(),
                uid = $('#uid').val();

            if (pass1 == pass2) {
                $.post("../process_update_one_user.php", {
                    data: JSON.stringify({
                        uid: uid,
                        password: pass1
                    })
                }).done(function (data) {
                    alert('Mot de passe changé avec succès');

                    $('#pass1').val('');
                    $('#pass2').val('');
                });
            } else {
                alert('Vos 2 mots de passe ne sont pas identiques.');
            }

        });

    });
</script>
</html>