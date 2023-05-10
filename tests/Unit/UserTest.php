<?php

namespace Tests\Unit;



use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_user(): void
    {
        $data = [
            'name' => 'Marina',
            'email' => 'marina.gabarasvili@gmail.com',
            'password' => Hash::make('12345')
        ];

        $response = $this->post('/api/auth/register', $data);

        $users = User::all()->toArray();

        $response->assertStatus(200);
        $this->assertEquals(1, $users[0]['id']);
    }

    /** @test */
    public function it_can_not_register_user_with_wrong_email(): void
    {
        $data = [
            'name' => 'Marina',
            'email' => 'random',
            'password' => Hash::make('12345')
        ];

        $response = $this->post('/api/auth/register', $data);

        $users = User::all()->toArray();

        $response->assertStatus(302);
        $this->assertEquals(0, count($users));
    }

    /** @test */
    public function it_can_not_register_user_without_required_data(): void
    {
        $data = [
            'name' => 'Marina',
            'email' => 'random',
            'password' => ''
        ];

        $response = $this->post('/api/auth/register', $data);

        $users = User::all()->toArray();

        $response->assertStatus(302);
        $this->assertEquals(0, count($users));
    }

    /** @test */
    public function it_can_not_register_user_with_same_email(): void
    {
        $data = [
            'name' => 'Marina',
            'email' => 'marina.gabarasvili@gmail.com',
            'password' => Hash::make('12345')
        ];

        $response = $this->post('/api/auth/register', $data);
        $response->assertStatus(200);

        $data = [
            'name' => 'Marina',
            'email' => 'marina.gabarasvili@gmail.com',
            'password' => Hash::make('12345')
        ];

        $response = $this->post('/api/auth/register', $data);

        $users = User::all()->toArray();

        $response->assertStatus(302);
        $this->assertEquals(1, count($users));
    }

    /** @test */
    public function it_can_login_user_with_correct_credentials(): void
    {
        $data = [
            'name' => 'Marina',
            'email' => 'marina.gabarasvili@gmail.com',
            'password' => '12345'
        ];

        $response = $this->post('/api/auth/register', $data);
        $response->assertStatus(200);

        $data = [
            'email' => 'marina.gabarasvili@gmail.com',
            'password' => '12345'
        ];

        $response = $this->post('/api/auth/login', $data);

        $user = User::first();

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_can_not_login_user_with_wrong_email(): void
    {
        $data = [
            'name' => 'Marina',
            'email' => 'marina.gabarasvili@gmail.com',
            'password' => '12345'
        ];

        $response = $this->post('/api/auth/register', $data);
        $response->assertStatus(200);

        $data = [
            'email' => 'marina.gabarasvi@gmail.com',
            'password' => '12345'
        ];

        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(401);
        $this->assertGuest();
    }

    /** @test */
    public function it_can_not_login_user_with_wrong_password(): void
    {
        $data = [
            'name' => 'Marina',
            'email' => 'marina.gabarasvili@gmail.com',
            'password' => '12345'
        ];

        $response = $this->post('/api/auth/register', $data);
        $response->assertStatus(200);

        $data = [
            'email' => 'marina.gabarasvili@gmail.com',
            'password' => 'wrong_password'
        ];

        $response = $this->post('/api/auth/login', $data);

        $response->assertStatus(401);
        $this->assertGuest();
    }
}
