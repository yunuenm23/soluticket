<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Google extends Model{
	
	protected $table = 'googleads';
	protected $fillable = ['seccion','script'];
}