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

        $filter = "(objectClass=posixGroup)";

        ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);

        $result = ldap_search($ldap_connection,$base_dn, $filter) or exit("Unable to search");

        $entries = ldap_get_entries($ldap_connection, $result);

        return $entries;
    }

    function findUsersOfGroup($ldap_connection, $base_dn, $group) {

        $filter = "(cn=$group)";

        ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);

        $result = ldap_search($ldap_connection,"ou=group," . $base_dn, $filter) or exit("Unable to search");
        $entries = ldap_get_entries($ldap_connection, $result);

        return $entries;
    }

    function findUsersNotOfGroup($ldap_connection, $base_dn, $group) {

        //$filter = "(!(cn=$group))";

        $usersInGroup = findUsersOfGroup($ldap_connection, $base_dn, $group);

        $members = [];
        foreach($usersInGroup[0]["memberuid"] as $member){
            array_push($members, $member);
        }

        $notMembers = [];
        $users = findAllUsers($ldap_connection, $base_dn);
        foreach ($users as $user) {
            if($user['uid'][0]) {
                if (!in_array($user['uid'][0], $members)) {
                    array_push($notMembers, $user);
                }
            }
        }

        return $notMembers;
    }

    function findOneUser($ldap_connection, $base_dn, $uid) {

        $filter = "(uid=$uid)";

        ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);

        $result = ldap_search($ldap_connection,"ou=people," . $base_dn, $filter) or exit("Unable to search");
        $entries = ldap_get_entries($ldap_connection, $result);

        return $entries;
    }

?>
