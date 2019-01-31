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

    $cn = htmlspecialchars($json['name'] . ' ' . $json['firstname']);
    $givenName = htmlspecialchars($json['firstname']);
    $sn = htmlspecialchars($json['name']);
    $uid = htmlspecialchars($json['login']);
    $homedirectory = htmlspecialchars('/home/' . $json['login']);
    $password = htmlspecialchars($json['password']);

    $ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");

    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

    $random = rand(1110, 2110);

    if ($ds) {

        $r = ldap_bind($ds, $ldap_dn,"bite");
        $info["cn"] = $cn;
        $info["givenName"] = $givenName;
        $info["sn"] = $sn;
        $info["uid"] = $uid;
        $info["homeDirectory"] = $homedirectory;
        $info['userPassword'] = $password;
        $info["uidNumber"] = $random;
        $info["gidNumber"] = $random;
        $info["loginShell"] = "/bin/bash";
        $info['objectClass'] = ["top", "person", "organizationalPerson" ,"inetOrgPerson", "posixAccount", "shadowAccount"];

        $r = ldap_add($ds,"cn=$cn,dc=declercq,dc=teub",$info);

        $sr = ldap_search($ds,"dc=declercq,dc=teub","cn=$cn");

        $info = ldap_get_entries($ds,$sr);
    }

    ldap_close($ds);

    header('Content-type: application/json');
    echo json_encode(['success' => true]);

