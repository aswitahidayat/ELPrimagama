<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'barcodekurikulum';

    /**
     * The connection name for the model.
     *
     * @var string
     */
     protected $connection = 'sqlsrvMaster';
}
