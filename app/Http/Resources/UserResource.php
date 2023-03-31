<?php

namespace App\Http\Resources;

use App\Models\WalletsByUsers;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => ImageResource::collection($this->images),
            'phone' => $this->phone_number,
            'role' => $this->roles,
            'idetification_card' => $this->identification_card,
            'device_token' => $this->device_token
        ];
    }
}
