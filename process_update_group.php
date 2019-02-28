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

$cn = htmlspecialchars($json['cn']);
$gid = htmlspecialchars($json['gid']);
$description = htmlspecialchars($json['description']);

$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");

ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

$random = rand(1110, 2110);

if ($ds) {

    $r = ldap_bind($ds, $ldap_dn,"bite");
    $info["cn"] = $cn;
    $info["description"] = $description;
    $info["gidNumber"] = $random;
    $info["objectClass"] = ["top", "posixGroup"];

    $r = ldap_modify($ds,"cn=$cn,ou=group,dc=declercq,dc=teub",$info);

    $sr = ldap_search($ds,"ou=group,dc=declercq,dc=teub","gidnumber=$gid");

    $info = ldap_get_entries($ds,$sr);
}

ldap_close($ds);

header('Content-type: application/json');
echo json_encode(['success' => true]);
