<?php

namespace api;

use lib\Extension\RetryTrait;
use lib\PageObject\User\User;
use lib\Helper\ApiHelper;
use PHPUnit\Framework\TestCase;

class ApiTestCases extends TestCase
{
    public function setUp():void
    {
        parent::setUp();
        $this->user = new User;
        $this->apiHelper = new ApiHelper();
        //Generate random username, email, pass by faker
        $this->user = $this->user->generateRandomUser();
    }

    //One more rerun in case of error
    use RetryTrait;
    /**
     * @retry 2
     */

    public function test_Create_Request_Empty()
    {
        //Send empty request
        $response = $this->apiHelper->userCreateRequest(400);
        //Check response has false success state
        $this->assertEquals(false,$response['success']);
        //Check message is "A username is required"
        $this->assertEquals("A username is required",$response['message'][0]);
    }
    public function test_Create_Request_Username_Only()
    {
        //Send request with only username
        $response = $this->apiHelper->userCreateRequest(400,$this->user->username);
        //Check response has false success state
        $this->assertEquals(false,$response['success']);
        //Check message is "An Email is required"
        $this->assertEquals("An Email is required",$response['message'][0]);
    }
    public function test_Create_Request_Email_Only()
    {
        //Send request with only email
        $response = $this->apiHelper->userCreateRequest(400,'','',$this->user->email);
        //Check response has false success state
        $this->assertEquals(false,$response['success']);
        //Check message is "A username is required"
        $this->assertEquals("A username is required",$response['message'][0]);
    }
    public function test_Create_Request_Pass_Only()
    {
        //Send request with only pass
        $response = $this->apiHelper->userCreateRequest(400,'',$this->user->password);
        //Check response has false success state
        $this->assertEquals(false,$response['success']);
        //Check message is "A username is required"
        $this->assertEquals("A username is required",$response['message'][0]);
    }
    public function test_Create_Request_Username_Email_Only()
    {
        //Send request with only username and email
        $response = $this->apiHelper->userCreateRequest(400,$this->user->username,'',$this->user->email);
        //Check response has false success state
        $this->assertEquals(false,$response['success']);
        //Check message is "A password for the user"
        $this->assertEquals("A password for the user",$response['message'][0]);
    }
    public function test_Create_Request_Username_Password_Only()
    {
        //Send request with only username and pass
        $response = $this->apiHelper->userCreateRequest(400,$this->user->username,$this->user->password);
        //Check response has false success state
        $this->assertEquals(false,$response['success']);
        //Check message is "An Email is required"
        $this->assertEquals("An Email is required",$response['message'][0]);
    }
    public function test_Create_Request_Password_Email_Only()
    {
        //Send request with only email and pass
        $response = $this->apiHelper->userCreateRequest(400,'',$this->user->password,$this->user->email);
        //Check response has false success state
        $this->assertEquals(false,$response['success']);
        //Check message is "A username is required"
        $this->assertEquals("A username is required",$response['message'][0]);
    }
    public function test_Create_Request_Duplicate_Username()
    {
        //Get existing account with id=1
        $getResultUser = $this->apiHelper->userGetRequest(200,null,null,null,1);
        //Send request with duplicate username
        $response = $this->apiHelper->userCreateRequest(400,$getResultUser->username,$this->user->password,$this->user->email);
        //Check response has false success state
        $this->assertEquals(false,$response['success']);
        //Check message is "This username is taken. Try another."
        $this->assertEquals("This username is taken. Try another.",$response['message'][0]);
    }
    public function test_Create_Request_Duplicate_Email()
    {
        //Get existing account with id=1
        $getResultUser = $this->apiHelper->userGetRequest(200,null,null,null,2);
        //Send request with duplicate email
        $response = $this->apiHelper->userCreateRequest(400,$this->user->username,$this->user->password,$getResultUser->email);
        //Check response has false success state
        $this->assertEquals(false,$response['success']);
        //Check message is "Email already exists"
        $this->assertEquals("Email already exists",$response['message'][0]);
    }
    public function test_Create_Request_Valid()//Broken
    {
        //Send valid request for user creation
        $response = $this->apiHelper->userCreateRequest(200,$this->user->username,$this->user->password,$this->user->email);
        //Check response has false success state
        $this->assertEquals(true,$response['success']);
        //Check message is "User Successfully created"
        $this->assertEquals("User Successfully created",$response['message'][0]);
        //Check account creation with get user
        $getResultUser = $this->apiHelper->userGetRequest(200,null,null,null,$this->user->id);
        $this->assertEquals($getResultUser->username,$this->user->username);
        $this->assertEquals($getResultUser->email,$this->user->email);
        $this->assertEquals($getResultUser->id,$this->user->id);
    }
    public function test_Create_Request_Valid_With_ID()
    {
        //Send valid request for user creation with additional id
        $response = $this->apiHelper->userCreateRequest(200,$this->user->username,$this->user->password,$this->user->email,$this->user->id);
        //Check response has false success state
        $this->assertEquals(true,$response['success']);
        //Check message is "User Successfully created"
        $this->assertEquals("User Successfully created",$response['message']);
        //Check account creation with get user
        $getResultUser = $this->apiHelper->userGetRequest(200,null,null,null,$this->user->id);
        $this->assertEquals($getResultUser->username,$this->user->username);
        $this->assertEquals($getResultUser->email,$this->user->email);
        $this->assertEquals($getResultUser->id,$this->user->id);
    }
}