<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Jenjang;
use App\Models\Kurikulum;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SmartEbook extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['nama', 'keterangan', 'jenjang1', 'jenjang2', 'kurikulum'];
     protected $appends = array('jenjang1data' , 'jenjang2data', 'kurikulumdata');

     public function getJenjang1dataAttribute()
     {
         $id = substr($this->jenjang1, 0, 2);
         try { 
             $jenjang1s = Jenjang::where('KodeJenjang', $id)->firstOrFail();
         } catch(ModelNotFoundException $e) {
             return '{}';
         }
         return $jenjang1s;
     }

     public function getJenjang2dataAttribute()
     {
         $ids = substr($this->jenjang2, 0, 2);
         try { 
             $jenjang2s = Jenjang::where('KodeJenjang', $ids)->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return '{}';
        }
         return $jenjang2s;
     }

     public function getKurikulumdataAttribute()
     {
         $ids = substr($this->kurikulum, 0, 2);
         try { 
             $kurikulums = Kurikulum::where('KodeKurikulum', $ids)->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return '{}';
        }
         return $kurikulums;
     }
}
