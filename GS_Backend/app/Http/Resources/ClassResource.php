<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'UserID'=> $this->user->userid,
            'user_id'=> $this->user->id,
            'myclass_id' => $this->myclass->id,
            'myclass_name' => $this->myclass->name,
            'fanme' => $this->user->fname,
            'lname' => $this->user->lname
        ];
    }
}
