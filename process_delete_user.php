<?php
/**
 * Created by PhpStorm.
 * User: m2info
 * Date: 28/01/19
 * Time: 16:31
 */

    include_once "ldap.php";

    $uid = htmlspecialchars($_GET['uid']);

    $ldap = "ldap";
    $basedn = "dc=declercq,dc=teub";

    $ds = ldap_connect($ldap) or die ("Could not connect to LDAP Server");

    if ($ds) {
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

        $r = ldap_bind($ds,"cn=admin," . $basedn,"bite");

        $filter = "(uid=" . $uid . ")";

        $result = ldap_search($ds,$basedn,$filter) or exit("Unable to search");

        $entries = ldap_get_entries($ds, $result);

        $dn = $entries[0]["dn"];

        ldap_delete($ds,$dn);
    }

    ldap_close($ds);

    header('Location: admin-front/admin.php');