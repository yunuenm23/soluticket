<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model{
	
	protected $table = 'faq';
	protected $fillable = ['titulo','descripcion','articulo','img_faq'];
}