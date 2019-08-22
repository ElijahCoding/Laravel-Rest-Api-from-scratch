<?php

namespace Tests\Feature;

use App\Contact;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function test_a_contact_can_be_added()
    {
        $this->withoutExceptionHandling();

        $this->post('/api/contacts', [
            'name' => 'Test Name',
            'email' => 'test@email.com',
            'birthday' => '05/14/1998',
            'company' => 'test Company'
        ]);

        $contact = Contact::first();

        $this->assertCount(1, $contact);
        $this->assertEquals('Test Name', $contact->name);
    }
}
