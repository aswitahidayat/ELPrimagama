<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'kotakab';

    /**
     * The connection name for the model.
     *
     * @var string
     */
     protected $connection = 'sqlsrvMaster';
}
