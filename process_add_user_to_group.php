<?php
/**
 * Created by PhpStorm.
 * User: m2info
 * Date: 28/01/19
 * Time: 16:31
 */

include_once "ldap.php";

$uid = $_POST['uid'];
$cn = $_POST['cn'];

$ldap_dn = "cn=admin,dc=declercq,dc=teub";

$ds = ldap_connect("localhost") or die ("Could not connect to LDAP Server");

ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

$random = rand(1110, 2110);

echo $uid . ' ' . $cn;

if ($ds) {

    $r = ldap_bind($ds, $ldap_dn,"bite");
    $info["memberuid"] = $uid;

    $r = ldap_mod_add($ds,"cn=$cn,ou=group,dc=declercq,dc=teub",$info);
}

ldap_close($ds);

header('Location:admin-front/admin.php');

