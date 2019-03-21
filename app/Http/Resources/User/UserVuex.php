<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Kodeine\Acl\Models\Eloquent\Role;

class UserVuex extends JsonResource
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
            'full_name' => $this->last_name.' '.$this->first_name,
            'role' => $this->roles[0]->slug,
            'perms' => $this->getPerms($this->roles[0]->id),
            'csrf_token' => $this->getCSRF($this->roles[0]->slug),
            'orders' => []
        ];
    }

    public function getPerms($role_id)
    {
        return Role::find($role_id)->getPermissions();
    }

    public function getCSRF($slug)
    {
        switch ($slug) {
            case 'superadmin': return csrf_token(); break;
            case 'admin': return csrf_token(); break;
            default: return ''; break;
        }
    }
}
