<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bakmi extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'dbo.m_bakmi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['nmfile', 'keterangan'];
}
