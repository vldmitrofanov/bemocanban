<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Columns extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function cards(){
        return $this->hasMany('App\Models\Cards', 'column_id', 'id')->orderBy('order', 'asc');
    }
}
