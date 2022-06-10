<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_require_fields_register()
    {
        
            $this->json('post', '/api/register', [])
            ->assertStatus(422)                
            ->assertJsonStructure(['success','message',
            'data' => ['name','email','password' ]
              
        ]);
         
        
    }
    public function testRequirePasswordConfirmation()
    {
        $register = [
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => 'userpass',
            'password_confirmation' => 'userpass123'

        ];
        
        $this->json('POST', 'api/register', $register)
            ->assertStatus(422)
            ->assertJsonStructure(['success','message',
            'data' => ['password' ]
              
        ]);
         
    }
     /**
     * A valid user can be registered.
     *
     * @return void
     */
    public function testRegistersAValidUser()
    {
        $user   = User::factory()->count(1)->create();    

        $response = $this->post('/api/register', [
            'name' => $user[0]->name,
            'email' => trim($user[0]->email).rand(0,4),
            'password' => 'password',
            'password_confirmation' => 'password',

        ]);

        $response->assertStatus(201);

    }

     /**
     * An invalid user is not registered.
     *
     * @return void
     */
    public function testDoesNotRegisterAnInvalidUser()
    {
        $user   = User::factory()->count(1)->create();    

        $response = $this->post('/api/register', [
            'name' => $user[0]->name,
            'email' => $user[0]->email,
            'password' => 'password123',
            'password_confirmation' => 'password',

        ]);

       // $response->assertSessionHasErrors();

        $this->assertGuest();
    }
   
}
