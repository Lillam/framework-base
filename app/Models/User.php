<?php

namespace App\Models;

use Vyui\Services\Database\Model;

class User extends Model
{
    protected array $attributes = [
        'first_name',
        'last_name',
        'email',
        'created_at',
        'updated_at',
        'password'
    ];
}
