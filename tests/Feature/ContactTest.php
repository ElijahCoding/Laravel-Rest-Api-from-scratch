<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\{Contact, User};

class ContactTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function test_an_unauthenticated_user_should_redirected_to_login()
    {
        $response = $this->post('/api/contacts', array_merge(
            $this->data(),
            ['api_token' => '']
        ));

        $response->assertRedirect('/login');

        $this->assertCount(0, Contact::all());
    }

    public function an_authenticated_user_can_add_a_contact()
    {
        
    }

    private function data()
    {
        return [
            'name' => 'Test Name',
            'email' => 'test@email.com',
            'birthday' => '05/14/1988',
            'company' => 'ABC String',
            'api_token' => $this->user->api_token,
        ];
    }
}
