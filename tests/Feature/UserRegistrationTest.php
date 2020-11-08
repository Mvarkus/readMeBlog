<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UserRegistrationTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_be_registred()
    {
        // $this->withoutExceptionHandling();
    
        $this->registerUser()->assertRedirect('/');
    
        $users = User::all();
        $this->assertCount(1, $users);

        return $users->first();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_without_first_name()
    {
        $this->registerUser('')->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_with_long_first_name()
    {
        $this->registerUser('asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasasdasdasdasdasdasdasdasdasdasdasdasdasdasasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasddasdasdasdasddasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd')->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_with_digit_in_first_name()
    {
        $this->registerUser(2)->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_without_second_name()
    {
        $this->registerUser('Maksim', '')->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_with_long_second_name()
    {
        $this->registerUser('Maksim', 'asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasasdasdasdasdasdasdasdasdasdasdasdasdasdasasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasddasdasdasdasddasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd')->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_with_digit_in_second_name()
    {
        $this->registerUser('Maksim', 2)->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_without_email()
    {
        $this->registerUser('Maksim', 'Varkus', '')->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_with_long_email()
    {
        $this->registerUser('Maksim', 'Varkus', 'asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasasdasdasdasdasdasdasdasdasdasdasdasdasdasasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasddasdasdasdasddasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasda@gmail.com')->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_with_invalid_email()
    {
        $this->registerUser('Maksim', 'Varkus', 2)->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_with_email_which_already_exists_in_database()
    {
        $this->registerUser();
        $this->actingAs(User::first())->post('/logout');
        $this->registerUser()->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_without_password()
    {
        $this->registerUser('Maksim', 'Varkus', 'dummy@dum.du', '')->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_with_short_password()
    {
        $this->registerUser('Maksim', 'Varkus', 'dummy@dum.du', '123')->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_be_registred_without_password_confirmation()
    {
        $this->post('/register', [
            'firstName'  => 'Maksim',
            'secondName' => 'Varkus',
            'email'      => 'dummy@dm.dumm',
            'password'   => 'password',
            'password_confirmation' => ''
        ])->assertSessionHasErrors();
    }

    private function registerUser(
        $firstName = 'Maksim',
        $secondName = 'Varkus',
        $email = 'dummy@dm.dumm',
        $password = 'password'
    ) {
        return $this->post('/register', [
            'firstName'  => $firstName,
            'secondName' => $secondName,
            'email'      => $email,
            'password'   => $password,
            'password_confirmation' => $password
        ]);
    }
}
