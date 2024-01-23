<?php

namespace App\Http\Resources\v1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CustomersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'name' => $this->name,
            'address' => $this->address,
            'contact' => $this->contact,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'image' => (string) env('APP_URL') . '/storage/customers/' . $this->image,
            'created_at' => Carbon::parse($this->created_at)->format('Y-M-d'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-M-d')
        ];
    }
}
