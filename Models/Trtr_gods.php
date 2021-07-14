<?php

namespace App\Models;

use App\Models\Trtr_alive;
use App\Models\Trtr_world;
use App\Models\Trtr_news;
use App\Models\Trtr_name;
use App\Models\Sacri;

use Illuminate\Database\Eloquent\Model;

class Trtr_gods extends Model {
    
    function gods_step(){
        $god_model = new Sacri();
        $names = new Trtr_name();
        $world = new Trtr_world();
        $god = $god_model->get_top_god();
        $aliver_model = new Trtr_alive();
        $news_model = new Trtr_news();
        $aliver = $aliver_model->inRandomOrder()->first();
        if($god->kills > 5 && rand(0, 50) === 0 && $aliver->old > 50){
            $aliver->love = 666;
            $aliver->work = -13;
            $aliver->live = -69;
            $news_model->gods_news(18, $names->get_only_name_by_id($aliver->name_id), $god->name);
            $aliver->update();
        }
        if($god->kills === 5 && rand(0, 50) === 0 && $aliver->old > 50){
            $aliver->love = 50;
            $aliver->work = 60;
            $aliver->live = 50;
            $news_model->gods_news(20, $names->get_only_name_by_id($aliver->name_id), $god->name);
            $aliver->update();
        }
        if($god->kills < 5 && rand(0, 50) === 0){
            $news_model->gods_cata(1, 19, $god->name);
            $world->cataclysm();
        }
        
    }


    

}
