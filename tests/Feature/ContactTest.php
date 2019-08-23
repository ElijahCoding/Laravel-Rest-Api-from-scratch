<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\{Contact, User};
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends TestCase
{
    protected $user;

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

    public function test_an_authenticated_user_can_add_a_contact()
    {
        $response = $this->post('/api/contacts', $this->data());

        $contact = Contact::first();

        $this->assertEquals('Test Name', $contact->name);
        $this->assertEquals('test@email.com', $contact->email);
        $this->assertEquals('05/14/1988', $contact->birthday->format('m/d/Y'));
        $this->assertEquals('ABC String', $contact->company);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson([
            'data' => [
                'contact_id' => $contact->id,
            ],
            'links' => [
                'self' => $contact->path(),
            ]
        ]);
    }

    public function test_fields_are_required()
    {
        collect(['name', 'email', 'birthday', 'company'])->each(function ($field) {
            $response = $this->post('/api/contacts',
                    array_merge($this->data(), [$field => '']));

            $response->assertSessionHasErrors($field);

            $this->assertCount(0, Contact::all());
        });
    }

    public function test_email_must_be_a_valid_email()
    {
        $response = $this->post('/api/contacts',
            array_merge($this->data(), ['email' => 'NOT AN EMAIL']));

        $response->assertSessionHasErrors('email');
        $this->assertCount(0, Contact::all());
    }

    public function test_birthdays_are_properly_stored()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/api/contacts',
            array_merge($this->data()));

        $this->assertCount(1, Contact::all());
        $this->assertInstanceOf(Carbon::class, Contact::first()->birthday);
        $this->assertEquals('05-14-1988', Contact::first()->birthday->format('m-d-Y'));
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
