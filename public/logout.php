<?php
include '../config/config.php';

$session = new Session();

if($session->exists('USER')) {
    $session->remove('USER');
}

header('Location: login.php');
die;