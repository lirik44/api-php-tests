<?php

namespace lib\Helper;

use function PHPUnit\Framework\assertEquals;

class ApiHelper
{
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function userCreateRequest($expectedStatusCode,$username = null,$password = null,$email = null,$id = null)
    {
        $response =$this->client->post(HTTPS_SCHEMA.HOST.USER_CREATE,['http_errors' => false, 'form_params' => [
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'id' => $id,
        ]]);
        $statusCode = $response->getStatusCode();
        assertEquals($expectedStatusCode,$statusCode);
        $response = json_decode($response->getBody(), true);
        return $response;
    }

    public function userGetRequest($expectedStatusCode,$username = null,$password = null,$email = null,$id = null)
    {
        $response = $this->client->get(HTTPS_SCHEMA.HOST.USER_GET,['query' => [
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'id' => $id,
            ]]);
        $statusCode = $response->getStatusCode();
        assertEquals($expectedStatusCode,$statusCode);
        $response = json_decode($response->getBody(), true);
        $getResultUser = new ApiHelper();
        $getResultUser->username = $response[0]['username'];
        $getResultUser->email = $response[0]['email'];
        $getResultUser->password = $response[0]['password'];
        $getResultUser->id = $response[0]['id'];
        return $getResultUser;
    }
}
