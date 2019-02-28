<?php
/**
 * Created by PhpStorm.
 * User: m2info
 * Date: 26/02/19
 * Time: 15:17
 */

$targetdir = 'uploads/';
// name of the directory where the files should be stored
$targetfile = $targetdir.$_FILES['groups']['name'];

$ldap_dn = "cn=admin,dc=declercq,dc=teub";
$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

if (move_uploaded_file($_FILES['groups']['tmp_name'], $targetfile)) {
    // file uploaded succeeded
    $strJsonFileContents = file_get_contents($targetfile);
    $array = json_decode($strJsonFileContents, true);

    $r = ldap_bind($ds, $ldap_dn,"bite");

    echo '<pre>';
        var_dump($array);
    echo '</pre>';

    foreach ($array as $item) {
        if($item["cn"][0]){
            $info['objectclass'] = ["top","posixGroup"];
            $info["description"] = $item["description"][0];
            $info["cn"] = $item["cn"][0];
            $info["gidNumber"] = $item["gidnumber"][0];

            $r = ldap_add($ds,"cn=" . $item["cn"][0] . ",ou=group,dc=declercq,dc=teub",$info);
        }
    }

    header('Location: admin-front/admin.php');
} else {
    // file upload failed
    echo 'ERREUR !!';
}