<?php

	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Trtr_sub_name extends Model {
	
	    protected $table = "trtr__sub_name";
	    protected $fillable = ['id', 'type', 'name'];
		
		/*
			CODE:
			1 - убийца
			2 – фамилия
			3 - за работу
			4 - за любовь
			5 - за отдых
			6 - король убитый текущей властью
			7 - революционер
			8 - кровавый король
		*/
		
		public function get_random_name($type){
			$res = $this->where('type', $type)->inRandomOrder()->limit(1)->first(); //рандомное имя
			return $res->name;
		}
		
	}
