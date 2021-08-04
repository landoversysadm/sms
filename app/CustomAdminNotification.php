<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomAdminNotification extends Model
{
    protected $guarded = [];
    protected $table = "custom_admin_notifications";

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
