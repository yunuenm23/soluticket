<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detallevento extends Model{
	
	protected $table = 'detallevento';
	protected $fillable = ['eventos','precios','mapa','btn_manual','btn_automatico','detalles'];
}