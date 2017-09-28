<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Jenjang;
use App\Models\Kurikulum;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Post;

class SmartEbook extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'dbo.smartbook';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nmfile', 'keterangan', 'jenjang', 'jenjang2', 'kurikulum', 'uploadFile', 'fileName', 'fileType'];
    protected $appends = array('jenjangdata' , 'jenjang2data', 'myFile');

    public function getJenjangdataAttribute()
    {
        $id = substr($this->jenjang, 0, 2);
        try {
            $jenjang1s = Jenjang::where('KodeJenjang', $id)->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return new Collection;
        }
        return $jenjang1s;
    }

    public function getJenjang2dataAttribute()
    {
        $ids = substr($this->jenjang2, 0, 2);
        try { 
            $jenjang2s = Jenjang::where('KodeJenjang', $ids)->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return new Collection;
        }
        return $jenjang2s;
    }

    public function getMyFileAttribute()
    {
        $myFIle = [
            'uploadFile' => $this->uploadFile,
            'fileName' => $this->fileName,
            'fileType' => $this->fileType,
        ];
        
        return $myFIle; 
    }
}
