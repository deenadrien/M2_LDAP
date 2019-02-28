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

$uid = htmlspecialchars($json['uid']);
$password = htmlspecialchars($json['password']);

$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");

ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

if ($ds) {

    $r = ldap_bind($ds, $ldap_dn,"bite");
    $info['userPassword'] = $password;

    $r = ldap_modify($ds,"uid=$uid,ou=people,dc=declercq,dc=teub",$info);
}

ldap_close($ds);

header('Content-type: application/json');
echo json_encode(['success' => true]);