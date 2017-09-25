<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mititi extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'dbo.m_titi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['nmfile', 'keterangan'];
}
