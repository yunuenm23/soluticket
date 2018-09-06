<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model{
	
	protected $table = 'banners';
	protected $fillable = ['seccion','url','img_banner'];
}