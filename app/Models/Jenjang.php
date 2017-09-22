<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenjang extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'jenjang';

    /**
     * The connection name for the model.
     *
     * @var string
     */
     protected $connection = 'sqlsrvMaster';
}
