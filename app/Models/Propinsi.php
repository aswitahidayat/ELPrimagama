<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propinsi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'propinsi';

    /**
     * The connection name for the model.
     *
     * @var string
     */
     protected $connection = 'sqlsrvMaster';
}
