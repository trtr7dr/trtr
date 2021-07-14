<?php

    namespace App\Models;
	
    use Illuminate\Database\Eloquent\Model;

    class Trtr_location extends Model {

	protected $table = "trtr__location";
	protected $fillable = ['id', 'name'];


	public function get_name_by_id($id){
		$res = $this->where('id', $id)->pluck('name');
		return $res[0];
	}
	public function get_id_by_code_name($name){
		$res = $this->where('code_name', $name)->first();
		return $res->id;
	}

}
    