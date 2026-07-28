<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'profile_picture',
        'password',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'has_dl',
        'dl_number',
        'dl_expiry',
        'dl_file',
        'alt_id_type',
        'alt_id_number',
        'alt_id_file',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
}
