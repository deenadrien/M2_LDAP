<?php


include_once "../ldap.php";

$json = json_decode($_POST['data'], true);

$gidNumber = $json['gidNumber'];


$ldap = "ldap";
$basedn = "dc=declercq,dc=teub";

$ldap_connection = open($ldap);

$filter = "(gidnumber=" . $gidNumber . ")";

ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);

$result = ldap_search($ldap_connection,$basedn, $filter) or exit("Unable to search");

$entries = ldap_get_entries($ldap_connection, $result);

header('Content-type: application/json');

echo json_encode(['group' => $entries]);
