<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class project extends Model
{
//    use SoftDeletes;
//
//    protected $fillable = ["project_name","WBS","pid","created_at","updated_at"];
    protected $table="project";
    protected $primaryKey="id";
    public $timestamps=false;
}
