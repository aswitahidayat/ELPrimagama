<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanTerbaca extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'tr_berita';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['otori', 'id_berita'];
}
