<?php

namespace Src\Model;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = "followes";

    protected $guarded = [];

    public $timestamps = false;
}
