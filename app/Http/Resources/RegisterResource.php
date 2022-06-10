<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class RegisterResource extends JsonResource
{
    public static $wrap = 'user';

    public function toArray($request): array
    {
                return [
                    'name' => $this->name,
                   'email' => $this->email,
                   'password' => Hash::make($this->password),
                 ];
    }
}

