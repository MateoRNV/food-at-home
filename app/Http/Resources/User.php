<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        switch ($this->type) {
            case "C":
                $type = "Customer";
                break;
            case "EC":
                $type = "Cook";
                break;
            case "ED":
                $type = "Deliveryman";
                break;
            case "EM" :
                $type = "Manager";
                break;
            default:
                $type = "Unknown";
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => $this->photo_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'blocked' => $this->blocked,
            'logged_at' => $this->logged_at,
            'available_at' => $this->available_at,
            'deleted_at' => $this->deleted_at,
            'type' => $this->type,
            'type_str' => $type
        ];
    }

}
