<?php
/**
 * Created by PhpStorm.
 * User: m2info
 * Date: 28/01/19
 * Time: 11:34
 */


    function open($hostname)
    {
        if (ldap_connect($hostname)) {
            return ldap_connect($hostname);
        }
    }

    function close($ldap){
        ldap_close($ldap);
    }

    function exist($login, $ldap_connection, $base_dn) {
        $filter = "(uid=" . $login . ")";

        $result = ldap_search($ldap_connection,$base_dn,$filter) or exit("Unable to search");

        $entries = ldap_get_entries($ldap_connection, $result);

        if ($entries) {
            return $entries;
        }

        return false;
    }

    function findAllUsers($ldap_connection, $base_dn) {

        $filter = "(uid=*)";

        ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);

        $result = ldap_search($ldap_connection,$base_dn, $filter) or exit("Unable to search");
        $entries = ldap_get_entries($ldap_connection, $result);

        return $entries;
    }

    function findAllGroups($ldap_connection, $base_dn) {

        $filter = "(ou=*)";

        ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);

        $result = ldap_search($ldap_connection,$base_dn, $filter) or exit("Unable to search");

        $entries = ldap_get_entries($ldap_connection, $result);

        return $entries;
    }







/*if(ldap_bind($ldap_con, $ldap_dn, $ldap_password)){

    $filter = "(uid=adrien)";
    $result = ldap_search($ldap_con,$basedn,$filter) or exit("Unable to search");

    $entries = ldap_get_entries($ldap_con, $result);

} else {
    echo "Invalid user/pass or other erros!";
}*/



?>
