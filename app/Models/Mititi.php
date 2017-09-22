<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mititi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['file', 'keterangan'];
}
