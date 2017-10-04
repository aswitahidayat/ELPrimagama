<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'tblberita';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['Judul', 'Keterangan', 'Tanggal', 'otori'];
}
