<?php

    namespace App\Models;
	
    use Illuminate\Database\Eloquent\Model;

    class Trtr_name extends Model {

	protected $table = "trtr__name";
	protected $fillable = ['id', 'name', 'sex'];

	public function get_random_new(){
		$sex = rand(1,2); //рандомный пол
		$res = $this->where('sex', $sex)->inRandomOrder()->limit(1)->first(); //рандомное имя соответствующего пола
		return $res;
	}
	
	public function get_name_by_id($id){
            return $this->where('id', $id)->pluck('name');
	}
        public function get_only_name_by_id($id){
            $res = $this->where('id', $id)->pluck('name');
            return $res[0];
        }
        public function get_only_sex_by_id($id){
            $res = $this->where('id', $id)->pluck('sex');
            return $res[0];
        }
}
    