<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class BlogPublicacion extends Model{
	
	protected $table = 'blog_post';
	protected $fillable = ['titulo','descripcion','fecha','articulo','img_blog'];
}