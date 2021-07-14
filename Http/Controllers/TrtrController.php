<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Trtr_world;
use App\Http\Controllers\Controller;
use App\Models\Trtr_alive;
use App\Models\Trtr_name;
use App\Models\Trtr_position;
use App\Models\Trtr_news;
use App\Models\Trtr_king;
use App\Models\Trtr_gods;
use App\Models\Trtr_creators;
use App\Models\Trtr_location;
use App\Models\Sacri;
use App\Models\Number;
use App\Models\Ukrf_challenger;
use App\Models\Ukrf_world;

class TrtrController extends Controller {

    public function index() {
        $data = [];

        $world = new Trtr_world();
        $world->check_world();

        $alive = new Trtr_alive();

        $gods = new Sacri();

        $data['god'] = $gods->get_top_god();

        $data['world'] = $world->where('description', '')->first();
        $data['alive'] = $alive->where('death', 0)->get();
        $data['deadmen'] = $alive->where('death', 1)->get();

        $names = new Trtr_name();
        $positions = new Trtr_position();

        foreach ($data['alive'] as $el) {
            $el['name'] = $names->get_name_by_id($el['name_id']);
            $el['position'] = $positions->get_pos_by_id($el['id']);
            $el['steps'] = $positions->get_steps_by_id($el['id']);
        }

        foreach ($data['deadmen'] as $el) {
            $el['name'] = $names->get_name_by_id($el['name_id']);
        }


        $locations = new Trtr_location();
        $data['location'] = $locations->all();

        $data['news'] = Trtr_news::orderBy('id', 'desc')->paginate(15);
        
        $ukrf_challenger_model = new Ukrf_challenger();
        $ukrf_world_model = new Ukrf_world();
        $ukrf_world = $ukrf_world_model->get_world();
       
        $ukrf_world[0]['king'] = $ukrf_challenger_model->get_name($ukrf_world[0]['king_id']);
        $data['ukrf'] = DB::table('users')->where('id', $ukrf_world_model->get_king_id())->first();
        $data['ukrf_k'] = $ukrf_world[0]->king;

        return view('site.templates.svc.trtr', ['data' => $data]);
    }

    function test() {
         $world = new Trtr_world();
        $world_upd = $world->where('description', '')->first();
        $world_upd->instant = $world_upd->instant + 1; //+1 мгновение

        $positions = new Trtr_position();
        $news = new Trtr_news();
        $world_upd->dust = $world_upd->dust + $positions->get_new_dust(); //расчет прибыли по пыли
        //$news->new_dust_get($world_upd->dust); //новость про пыль
        $world_upd->update();
        $alive = new Trtr_alive();
        $alive->new_old();
        $alive->new_character_change(); //изменения в характере в результате работы/любви/отдыха
        $positions->new_positions(); //перемещение персонажей в зависимости от их хар-к.
        
        $max_new = 0;
        while($max_new < 10){
            $news->new_aliver($alive->new_charter()); //проверка рождения новых персонажей
            $max_new++;
        }
        
        $max_dead = 0;
        $rand_d = rand(0,1);
        /* @var $rand_d type */
        $news->old_aliver($alive->old_dead()); //смерти от старости
        while($max_dead < 10 && $rand_d === 1){
            $d_name = $alive->death_charter();
            $news->death_aliver($d_name); //время смертей?
            $rand_d = rand(0,1);
            $max_dead++;
        }
	
	$king_model = new Trtr_king();
	$king_model->king_step();
        
        $gods = new Trtr_gods();
        $gods->gods_step();
        
        $creators_model = new Trtr_creators();
        $creators_model->creators_step();
	
        $world->cataclysm();
        $world->check_end();
    }

}
