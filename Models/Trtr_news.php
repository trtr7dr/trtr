<?php

namespace App\Models;

use App\Models\Trtr_code;
use Illuminate\Database\Eloquent\Model;

class Trtr_news extends Model {

    protected $table = "trtr__news";
    protected $fillable = ['id', 'who_id', 'where_id', 'result'];

    public function new_world_gen($num) {
        $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = '<b>'.$code->get_text(1) . ' ' . $num.'</b>';
        $this->insert($news);
    }
    
	public function simple_news($type){
        $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = $code->get_text($type);
        $this->insert($news);
    }
    
    public function new_dust_get($x) {
        if ($x > 0) { //нет новостей, если нет дохода
            $code = new Trtr_code();
            $news = [];
            $news['id'] = $this->max('id') + 1;
            $news['result'] = $x . ' ' . $code->get_text(2);
            $this->insert($news);
        }
    }

    public function new_aliver($names) { //новость о рождении
        if ($names[0] != '') {

            $code = new Trtr_code();
            $news = [];
            $news['id'] = $this->max('id') + 1;
            //dd($names);
            $news['result'] = $names[0] . ' и ' . $names[1] . ' ' . $code->get_text(3) . ' "' . $names[2] . '".';

            $this->insert($news);
        }
    }

    public function death_aliver($name) {
        if ($name) {
            $code = new Trtr_code();
            $news = [];
            $news['id'] = $this->max('id') + 1;
            $news['result'] = $name . ' ' . $code->get_text(4);
            $this->insert($news);
        }
    }
    public function old_aliver($name){
        if($name){
            $code = new Trtr_code();
            $news = [];
            $news['id'] = $this->max('id') + 1;
            $news['result'] = $name . ' ' . $code->get_text(17);
            $this->insert($news);
        }
    }

    public function end_world($wid, $code_id) {
        $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = '<b>Королевство ТырТыр №'.$wid .' '. $code->get_text($code_id).'</b>';
        $this->insert($news);
    }
    
    public function cata_news($code_id){
        $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = '<span class="cata">'.$code->get_text($code_id).'</span>';
        $this->insert($news);
    }
    
    public function killer_news($who, $die){
        $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = $who.' '.$code->get_text(11).' '.$die.'.';
        $this->insert($news);
    }
    
    public function new_king_news($name){
        $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = $code->get_text(12).' '.$name[0].'.';
        $this->insert($news);
    }
    public function king_news($type, $name){
        $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = $name[0].' '.$code->get_text($type).' '.$name[0].'.';
        $this->insert($news);
    }
    public function king_killer($die, $king){
	    $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = $king[0].' '.$code->get_text(15).' '.$die[0].'.';
        $this->insert($news);
    }
    
    public function gods_news($code, $name, $god) {
        $code_m = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = $god.' '.$code_m->get_text($code) . ' ' . $name;
        $this->insert($news);
    }
    
    public function gods_cata($type, $code, $name){
        $code_m = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = $name.' '.$code_m->get_text($code);
        $this->insert($news);
    }
    
    public function new_creator($type, $name){
        $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = $name[0].' '.$code->get_text($type);
        $this->insert($news);
    }
    
    public function vamp_news($vam, $targ, $type){
        $code = new Trtr_code();
        $news = [];
        $news['id'] = $this->max('id') + 1;
        $news['result'] = $vam[0] . ' ' . $code->get_text($type) . $targ[0];
        $this->insert($news);
    }

}
