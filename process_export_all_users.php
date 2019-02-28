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


    $data = findAllUsers($connection,$basedn);

    header('Content-type: application/json');

    $file="tmp/users.json";
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

    header("Content-Type: application/octet-stream");
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=users.json");
    readfile($file);

