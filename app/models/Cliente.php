<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model{
	
	protected $table = 'clientes';
	protected $fillable = ['thumbnail'];
}