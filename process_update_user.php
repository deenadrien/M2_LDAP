<?php
/**
 * Created by PhpStorm.
 * User: m2info
 * Date: 28/01/19
 * Time: 16:31
 */

    include_once "ldap.php";

    $json = json_decode($_POST['data'], true);

    $ldap_dn = "cn=admin,dc=declercq,dc=teub";

    $cn = htmlspecialchars($json['sn'] . ' ' . $json['givenName']);
    $givenName = htmlspecialchars($json['givenName']);
    $sn = htmlspecialchars($json['sn']);
    $uid = htmlspecialchars($json['uid']);
    $homedirectory = htmlspecialchars('/home/' . $uid);
    $password = htmlspecialchars($json['password']);

    $ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");

    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

    if ($ds) {

        $r = ldap_bind($ds, $ldap_dn,"bite");
        $info["cn"] = $cn;
        $info["givenName"] = $givenName;
        $info["sn"] = $sn;
        $info["homeDirectory"] = $homedirectory;
        $info['userPassword'] = $password;
        $info["loginShell"] = "/bin/bash";
        //$info["uidNumber"] = $random;
        //$info["gidNumber"] = $random;
        $info['objectClass'] = ["top", "person", "organizationalPerson" ,"inetOrgPerson", "posixAccount", "shadowAccount"];

        $r = ldap_modify($ds,"uid=$uid,ou=people,dc=declercq,dc=teub",$info);


        $sr = ldap_search($ds,"dc=declercq,dc=teub","uid=$uid");

        $info = ldap_get_entries($ds,$sr);
    }

    ldap_close($ds);

    header('Content-type: application/json');
    echo json_encode(['success' => true]);

