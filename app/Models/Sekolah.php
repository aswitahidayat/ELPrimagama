<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Propinsi;
use App\Models\Kota;

class Sekolah extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'dbo.asal_sekolah';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['asal_sekolah', 'propinsi', 'kota'];
     protected $appends = array('propinsiData' , 'kotaData');

     public function getPropinsiDataAttribute()
     {
         $id = substr($this->propinsi, 0, 2);
         try { 
             $propinsis = Propinsi::where('RecID', $id)->firstOrFail();
         } catch(ModelNotFoundException $e) {
             return '{}';
         }
         return $propinsis;
     }

     public function getKotaDataAttribute()
     {
         $id = substr($this->kota, 0, 4);
         try { 
             $kotas = Kota::where('RecID', $id)->firstOrFail();
         } catch(ModelNotFoundException $e) {
             return '{}';
         }
         return $kotas;
     }
}
