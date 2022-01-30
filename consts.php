<?php
$local_config = __DIR__."/consts.local.php";
if(file_exists($local_config))
    include_once($local_config);
const HOST = 'cci-gwp-adonis-api.herokuapp.com/';
const USER_CREATE = 'user/create';
const USER_GET = 'user/get';
const HTTP_SCHEMA = 'http://';
const HTTPS_SCHEMA = 'https://';