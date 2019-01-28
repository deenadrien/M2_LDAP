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

    $r = ldap_bind($ds, $ldap_dn,"bite");

    $info["cn"] = $json['name'] . ' ' . $json['firstname'];
    $info["homedirectory"] = '/home/' . $json['login'];
    $info["sn"] = $json['name'];
    $info["givenName"] = $json['firstname'];
    $info["uid"] = $json['login'];

    //$r = ldap_add($);

