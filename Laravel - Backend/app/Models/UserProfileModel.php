<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfileModel extends Model
{
    protected $fillable = [
        'avatar_url', 'name', 'username', 'bio', 'github_link', 'blog_link',
        'company', 'location', 'public_repos', 'followers', 'following',
    ];
    public function __construct(array $data)
    {
        $this->avatar_url = $data['avatar_url'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->username = $data['login'] ?? null;
        $this->bio = $data['bio'] ?? null;
        $this->github_link = $data['html_url'] ?? null;
        $this->blog_link = $data['blog'] ?? null;
        $this->company = $data['company'] ?? null;
        $this->location = $data['location'] ?? null;
        $this->public_repos = $data['public_repos'] ?? null;
        $this->followers = $data['followers'] ?? null;
        $this->following = $data['following'] ?? null;
    }
}
