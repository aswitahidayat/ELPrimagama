<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rumus extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
     protected $table = 'dbo.m_pulus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['nmfile', 'keterangan', 'uploadFile', 'fileName', 'fileType'];
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
