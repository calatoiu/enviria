<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Furnizor extends Model
{
    use HasFactory;
    protected $table = 'furnizor';
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

}
