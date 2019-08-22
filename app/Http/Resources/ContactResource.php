<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'contact_id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'birthday' => $this->birthday->format('m/d/Y'),
                'company' => $this->company,
                'last_updated' => $this->updated_at->diffForHumans(),
            ],
            'links' => [
                'self' => $this->path(),
            ]
        ];
    }
}
