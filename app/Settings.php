<?php

namespace Thanatos;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model {

	protected $table = 'settings';
	public $timestamps = true;
	protected $fillable = array('meta_key', 'meta_value');
	protected $visible = array('meta_key', 'meta_value');

}