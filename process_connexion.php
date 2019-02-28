<?php
/**
 * Created by PhpStorm.
 * User: m2info
 * Date: 28/01/19
 * Time: 14:04
 */

    include_once "ldap.php";

    $ldap = "ldap";
    $basedn = "dc=declercq,dc=teub";

    $login = isset($_POST['login']) ?  $_POST['login'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if ($login && $password) {

        if ($login == "admin") {
            $ldap_con = open($ldap);
            $ldap_dn = "cn=" . $login . "," . $basedn;
            ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);

            if (ldap_bind($ldap_con, $ldap_dn, $password)) {
                //ADMIN ET MDP CORRECT
                session_start();
                $_SESSION['secure'] = true;
                header('Location:admin-front/admin.php');
            } else {
                //SI MDP ERRONE REDIRECTION VERS PAGE DE LOG
                header('Location:index.php');
            }
        } else {
            $ldap_con = open($ldap);
            $user = exist($_POST['login'], $ldap_con, $basedn);

            if ($user) {
                $ldap_con = open($ldap);
                $ldap_dn = "uid=" . $login . ",ou=people," . $basedn;
                ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);

                if (ldap_bind($ldap_con, $ldap_dn, $password)) {
                    session_start();
                    $_SESSION['secure'] = true;
                    header('Location: user-front/user.php?uid=' . $login);
                } else {
                    header('Location:index.php?error=1');
                }
            } else {
                echo "Utilisateur non-trouvé";
            }
        }

    }

