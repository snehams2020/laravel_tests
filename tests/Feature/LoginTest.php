<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use factory;
use App\Models\User;
class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
       
        public function test_require_fields_login()
        {
            
                $this->json('post', '/api/login', [])
                ->assertStatus(422)                
                ->assertJsonStructure(['success','message',
                'data' => ['email','password']
                  
            ]);
                            
        }
        public function test_login_validUser()
        {
            $user   = User::factory()->count(1)->create();    
            $response = $this->post('/api/login', [
                'email' => $user[0]->email,
                'password' => 'password'
                ]);
    
                $response->assertStatus(200);

                $this->assertAuthenticatedAs($user[0]);
        }
         /**
     * An invalid user cannot be logged in.
     *
     * @return void
     */
    public function testDoesNotLoginAnInvalidUser()
    {
        $user   = User::factory()->count(1)->create();    

        $response = $this->post('/api/login', [
            'email' => $user[0]->email,
            'password' => 'invalid'
        ]);

       // $response->assertSessionHasErrors();

        $this->assertGuest();
    }

      
    
    }

