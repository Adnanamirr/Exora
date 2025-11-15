<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedContent extends Model
{
    protected $guarded = [];
//
//    public function template()
//    {
//        return $this->belongsTo(Template::class);
//    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
