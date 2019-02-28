<?php
/**
 * Created by PhpStorm.
 * User: m2info
 * Date: 26/02/19
 * Time: 15:17
 */

$targetdir = 'uploads/';
// name of the directory where the files should be stored
$targetfile = $targetdir.$_FILES['users']['name'];

$ldap_dn = "cn=admin,dc=declercq,dc=teub";
$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

if (move_uploaded_file($_FILES['users']['tmp_name'], $targetfile)) {

    $strJsonFileContents = file_get_contents($targetfile);
    $array = json_decode($strJsonFileContents, true);

    $r = ldap_bind($ds, $ldap_dn,"bite");

    foreach ($array as $item) {
         if ($item['uid'][0]) {
             $info["cn"] = $item['cn'][0];
             $info["givenName"] = $item['givenname'][0];
             $info["sn"] = $item['sn'][0];
             $info["uid"] = $item['uid'][0];
             $info["homeDirectory"] = $item['homedirectory'][0];
             $info['userPassword'] = $item['uid'][0];
             $info["uidNumber"] = $item['uidnumber'][0];
             $info["gidNumber"] = $item['gidnumber'][0];
             $info["loginShell"] = "/bin/bash";
             $info['objectClass'] = ["top", "person", "organizationalPerson", "inetOrgPerson", "posixAccount", "shadowAccount"];

             $r = ldap_add($ds, "uid=" . $item['uid'][0] . ", ou=people,dc=declercq,dc=teub", $info);
         }
    }

    header('Location: admin-front/admin.php');
} else {
    // file upload failed
    echo 'ERREUR !!';
}