<?php

class RegisterTest extends TestCase
{
    /**
     * Register a test user
     *
     * @return void
     */
    public function testRegisterUser()
    {
        $user = 'Test' . mt_rand(0, 99);
        $pass = 't3sTs_4r3_OP';

        $this->visit('register')
            ->see('Register')
            ->type($user  . '@thibaultmiclo.me', 'email')
            ->type($user, 'name')
            ->type($user, 'display')
            ->type($pass, 'password')
            ->type($pass, 'password_confirmation')
            ->press('Register')
            ->seePageIs('login')
            ->see('Welcome');
    }
}