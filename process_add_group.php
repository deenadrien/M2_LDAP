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

$description = htmlspecialchars($json['description']);
$title = htmlspecialchars($json['title']);

$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");

ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

$random = rand(1110, 2110);

if ($ds) {

    $r = ldap_bind($ds, $ldap_dn,"bite");
    $info['objectclass'] = ["top","posixGroup"];
    $info["description"] = $description;
    $info["cn"] = $title;
    $info["gidNumber"] = $random;


    $r = ldap_add($ds,"cn=" . $title . ",ou=group,dc=declercq,dc=teub",$info);
}

ldap_close($ds);

header('Content-type: application/json');
echo json_encode(['success' => true]);

