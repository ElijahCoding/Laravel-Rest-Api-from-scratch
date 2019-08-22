<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use App\Http\Resources\ContactResource;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    public function store()
    {
        $contact = request()->user()->contacts()->create($this->validateData());

        return (new ContactResource($contact))
               ->response()
               ->setStatusCode(Response::HTTP_CREATED);
    }

    private function validateData()
    {
        return request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'birthday' => 'required',
            'company' => 'required',
        ]);
    }
}
