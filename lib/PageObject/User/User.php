<?php

namespace lib\PageObject\User;

use Faker;

class User
{
    public $username = null;
    public $email = null;
    public $password = null;
    public $id = null;

    public function generateRandomUser()
    {
        $faker = Faker\Factory::create();
        $randomInt = $faker->randomDigit();

        $user = new User();
        $user->username = $faker->firstName().$faker->lastName().$randomInt;
        $user->email = "$user->username@test.com";
        $user->password = $faker->password();
        $user->id = $faker->randomNumber("6");
        return $user;
    }
}