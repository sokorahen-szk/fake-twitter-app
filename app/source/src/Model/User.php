<?php

namespace Src\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = [];

    // HACK: eloquentでid属性は自動的にint型にキャストされるっぽい。それの対策
    // https://yudy1152.hatenablog.com/entry/2019/04/19/132638
    public $incrementing = false;

    protected $keyType = 'string';
}
