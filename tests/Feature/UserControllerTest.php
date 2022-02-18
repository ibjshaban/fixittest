<?php

namespace Tests\Feature;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_get_all_users()
    {
        UserFactory::new()->count(2)->create();

        $this->getJson('api/users')->dump()
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'email',
                    ],
                ],
            ]);
    }

    public function test_api_create_user()
    {
        $this->postJson('api/users')
            ->assertStatus(422);
    }

    public function test_can_create_user()
    {
        $user = [
            'username' => 'Joe',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'mobile' => '059242',
        ];

        $this->postJson('api/users', $user)
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'id' => '1',
                    'username' => 'Joe',
                    'email' => 'testemail@test.com',
                    'mobile' => '059242',
                ],
            ]);

    }

    public function test_fields_create_user_mobile_is_require()
    {

        $user = [
            'username' => 'asdf0',
            'email' => 'asdfasdf@d.d',
            'password' => 'asdfasdf',
        ];

        $this->postJson('api/users', $user)
            ->assertInvalid('mobile');
    }

    public function test_fields_create_user_mobile_invalid_data_type()
    {

        $user = [
            'username' => 'asdf0',
            'email' => 'asdfasdf@d.d',
            'password' => 'asdfasdf',
            'mobile' => 'asdfa',
        ];

        $this->postJson('api/users', $user)
            ->assertInvalid('mobile');
    }

    public function test_invalid_fields_create_user_validation()
    {

        $user = [
            'username' => '',
            'email' => 'd.d',
            'mobile' => 'asdfasdf',
            'password' => ''
        ];

        $this->postJson('api/users', $user)
            ->assertJsonValidationErrors([
                'username',
                'email',
                'password',
                'mobile',
            ]);
    }
}
