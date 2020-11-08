<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserCredentialsUpdateTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_update_credentials()
    {
        $this->withoutExceptionHandling();
    
        $this->updateCredentials(
            $this->userData(),
            '/user'
        )->assertSessionHasNoErrors();

        $user = User::first();
        
        $this->assertSame('Maksim', $user->first_name);
        $this->assertSame('Varkus', $user->second_name);
    }

    /**
     * @test
     */
    public function user_cannot_update_credentials_without_first_name()
    {
        $this->updateCredentials(
            $this->userData(''),
            '/user'
        )->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function user_cannot_update_credentials_without_second_name()
    {
        $this->updateCredentials(
            $this->userData('Gena', ''),
            '/user'
        )->assertSessionHasErrors();
    }
    
    /**
     * @test
     */
    public function user_cannot_update_credentials_with_not_unique_email()
    {
        $this->updateCredentials(
            $this->userData(),
            '/user-email'
        )->assertSessionHasErrors();
    }

    private function userData(
        string $firstName = 'Maksim',
        string $secondName = 'Varkus',
        string $email = 'okarin@mail.ss'
    ) {
        return [
            'firstName' => $firstName,
            'secondName' => $secondName,
            'email' => $email
        ];
    }

    private function updateCredentials(
       array $userData,
       string $updatePath
    ) {
        $user = factory(User::class)->create([
            'first_name' => 'Maksim',
            'second_name' => 'Varkus',
            'email' => 'okarin@mail.ss'
        ]);
        
        switch ($updatePath) {
            case '/user':
                return $this->actingAs($user)->put('/user', [
                        'firstName'  => $userData['firstName'],
                        'secondName' => $userData['secondName']
                    ]);
            break;
            
            default:
                return $this->actingAs($user)->put('/user-email', [
                    'email' => $userData['email']
                ]);
            break;
        }
    }
}
