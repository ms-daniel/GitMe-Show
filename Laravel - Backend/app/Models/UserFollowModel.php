<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFollowModel extends Model
{
    protected $fillable = [
        'avatar_url', 'username', 'github_link'
    ];
    public function __construct(array $data)
    {
        $this->avatar_url = $data['avatar_url'] ?? null;
        $this->username = $data['login'] ?? null;
        $this->github_link = $data['html_url'] ?? null;
    }
}
