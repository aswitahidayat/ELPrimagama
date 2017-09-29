<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner     extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'tblinfo';

    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['judul', 'keterangan', 'img'];
     protected $appends = array('myFile');
     
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
