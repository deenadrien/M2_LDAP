<?php
/**
 * Created by PhpStorm.
 * User: m2info
 * Date: 26/02/19
 * Time: 14:32
 */

    include_once "ldap.php";

    $ldap = "ldap";
    $basedn = "dc=declercq,dc=teub";

    $connection = open($ldap);


    $data = findAllGroups($connection,$basedn);

    header('Content-type: application/json');

    $file="tmp/groups.json";
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

    header("Content-Type: application/octet-stream");
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=groups.json");
    readfile($file);

