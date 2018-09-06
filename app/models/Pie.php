<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Pie extends Model{
	
	protected $table = 'pie';
	protected $fillable = ['facebook','twitter','youtube'];
}