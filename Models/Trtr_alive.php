<?php

namespace App\Models;

use App\Models\Trtr_name;
use App\Models\Trtr_world;
use App\Models\Trtr_position;
use App\Models\Trtr_sub_name;
use Illuminate\Database\Eloquent\Model;


class Trtr_alive extends Model {

    protected $table = "trtr__alive";
    protected $fillable = ['id', 'name_id', 'king', 'sub_name', 'nick_name', 'kills', 'figure', 'work', 'love', 'live', 'death', 'old', 'creators'];
    public static $def_creator = 0;

    public function truncate_all() {
        $this->truncate();
    }

    public function new_aliver($subname, $creators) { //добавить нового персонажа
        $name_model = new Trtr_name();
        $name = $name_model->get_random_new();
        $new_alive = [];
        $position = new Trtr_position();

        $aid = $this->max('id') + 1;

        $pos = [];
        $pos['id'] = $position->max('id') + 1;
        $pos['alive_id'] = $aid;
        $pos['location'] = rand(1, 3);
        $pos['steps'] = 1;
        $position->insert($pos);

        $new_alive['id'] = $aid;
        $new_alive['name_id'] = $name->id;
        $new_alive['figure'] = $this->random_figure($name->sex);
        $new_alive['work'] = 50;
        $new_alive['love'] = 50;
        $new_alive['live'] = 50;
        $new_alive['creators'] = $creators;
        if ($subname) {
            $new_alive['sub_name'] = $subname;
        }
        $this->insert($new_alive);

        return $name->name;
    }

    public function new_world_alive() { //выжывальщики в новом мире
        $this->truncate_all();

        $population = rand(10, 20);
        $new_alive = [];
        $name_model = new Trtr_name();
        $iid = 0;
        for ($i = 0; $i < $population; $i++) {
            $new_alive['id'] = $iid;
            $iid++;
            $name = $name_model->get_random_new();
            $new_alive['name_id'] = $name->id;
            $new_alive['figure'] = $this->random_figure($name->sex);
            $new_alive['work'] = rand(30, 80);
            $new_alive['love'] = rand(30, 80);
            $new_alive['live'] = rand(30, 80);
            $this->insert($new_alive);
        }
    }

    private function random_figure($sex) {

        switch ($sex) {
            case 1:
                $res = '/assets/trtr/m/' . rand(1, 15) . '/';
                break;
            case 2:
                $res = '/assets/trtr/w/' . rand(1, 14) . '/';
                break;
        }
        return $res;
    }

    public function get_alive_by_id($id) {
        return $this->where('id', $id)->first();
    }

    public function get_live_alive_by_id($id) {
        return $this->where('id', $id)->where('death', '0')->first();
    }

    public function new_old() {
        $all = $this->get();
        foreach ($all as $el) {
            $el->old = $el->old + 1;
            $el->update();
        }
        // 
    }

    public function old_dead() {
        $all = $this->where('old', '>', 120)->limit(5)->get();
        
        foreach ($all as $el) {
	        $r = rand(1,5);
            if(rand(0, $el->old) > 120 && $r === 1 || ($el->creators === self::$def_creator && $r === 1 && rand(0, $el->old) > 500)){
                $this->kill_aliver($el->id);
                return $el->name;
            }
        }
        return false;
    }

    public function new_character_change() {

        /*
          работа -1 +1 -1
          любовь -1 +1 +1
          жизнь +1 -1
         */
        $this->work_change();
        $this->love_change();
        $this->live_change();
    }

    private function work_change() {
        $pos_model = new Trtr_position();
        $pos = $pos_model->where('location', '2')->get();

        $sub_name_model = new Trtr_sub_name();
        $sname = $sub_name_model->get_random_name(3);

        $alive = [];
        foreach ($pos as $el) {
            $alive = $this->get_live_alive_by_id($el['alive_id']);
            if (!is_null($alive)) {
                $alive->work = $alive->work - $el['steps'] + rand(-1, 4);
                $alive->live = $alive->live + rand(-2, 2) * $el['steps'];
                $alive->love = $alive->love + rand(-1, 1) * $el['steps'];

                if ($el['steps'] > 6) {
                    $alive->nick_name = $alive->nick_name . ' ' . $sname;
                }

                $alive->save();
            }
        }
    }

    private function love_change() {
        $pos_model = new Trtr_position();
        $pos = $pos_model->where('location', '3')->get();

        $sub_name_model = new Trtr_sub_name();
        $sname = $sub_name_model->get_random_name(4);

        $alive = [];
        foreach ($pos as $el) {
            $alive = $this->get_live_alive_by_id($el['alive_id']);
            if (!is_null($alive)) {
                $alive->work = $alive->work + rand(-1, 1) * $el['steps'];
                $alive->live = $alive->live + rand(-2, 2) * $el['steps'];
                $alive->love = $alive->love + $el['steps'] + rand(-1, 1);

                if ($el['steps'] > 15) {
                    $alive->nick_name = $alive->nick_name . ' ' . $sname;
                }

                $alive->save();
            }
        }
    }

    private function live_change() {
        $pos_model = new Trtr_position();
        $pos = $pos_model->where('location', '1')->get();

        $sub_name_model = new Trtr_sub_name();
        $sname = $sub_name_model->get_random_name(5);

        $alive = [];
        foreach ($pos as $el) {
            if (rand(0, 10) === 10) {
                $alive = $this->get_live_alive_by_id($el['alive_id']);
                if (!is_null($alive)) {
                    $alive->love = $alive->love + rand(-15, 15);
                    $alive->live = $alive->live + rand(-15, 15);
                    $alive->work = $alive->work + rand(-15, 15);

                    if ($el['steps'] > 6) {
                        $alive->nick_name = $alive->nick_name . ' ' . $sname;
                    }

                    $alive->save();
                }
            }
        }
    }

    //UPDATE `trtr_alive` SET `work`="50",`love`="50",`live`="50" WHERE 1
    public function get_priority($id) {
        $aliver = $this->where('id', $id)->first();

        $res = [];
        $res['work'] = rand(0, 50) + rand(0, $aliver->work);
        $res['love'] = rand(0, 50) + rand(0, $aliver->love);
        $res['live'] = rand(0, 50) + rand(0, $aliver->live);
        if ($aliver->work < 20 || $aliver->love < 20 || $aliver->live < 20 || $aliver->live > 90) {

            $res['sad'] = rand(0, max($res) * 2);
        }
        return array_keys($res, max($res))[0];
    }

    public function sub_name_change($x, $y, $z) { //выбор фамилии
        $res = NULL;
        if (is_null($x) && is_null($y)) {
            $res = $z;
        }
        return $res;
    }

    public function sub_child_change($x, $y, $z) { //выбор фамилии ребенка
        $res = NULL;
        if (is_null($x) && is_null($y)) {
            $res = $z;
        }
        if (!is_null($x) && is_null($y)) {
            $res = $x;
        }
        if (is_null($x) && !is_null($y)) {
            $res = $y;
        }
        if (!is_null($x) && !is_null($y) && $x === $y) {
            $res = $x;
        }

        return $res;
    }

    public function new_charter() {
        $sub_name_model = new Trtr_sub_name();
        $sname = $sub_name_model->get_random_name(2);
        $res = false;
        $lovers = $this->where('love', '>', 75)->where('death', 0)->inRandomOrder()->limit(2)->get();
        $names = new Trtr_name();
        if (count($lovers) > 1 && rand(1, 10) === 1) {  //новый персонаж рождается
            $i = 0;
            $flag = false;
            if ($names->get_only_sex_by_id($lovers[0]->name_id) !== $names->get_only_sex_by_id($lovers[1]->name_id)) {

                $child_name = $this->sub_child_change($lovers[0]->sub_name, $lovers[1]->sub_name, $sname);

                $per_name = $this->sub_name_change($lovers[0]->sub_name, $lovers[1]->sub_name, $sname);
                
                $arr_creat = [$lovers[0]->creators, $lovers[1]->creators];
                $creators = $arr_creat[rand(0, 1)];

                foreach ($lovers as $el) { //скинули характер до нейтрального
                    $res[$i] = $names->get_only_name_by_id($el->name_id);

                    if (!is_null($per_name)) {
                        $el->sub_name = $per_name;
                    }
                    $i++;
                    $el->love = 50;
                    $el->live = 50;
                    $el->work = 50;
                    $el->update();
                }

                $res[$i] = $this->new_aliver($child_name, $creators);
            }
        }
        return $res;
        //отдать массив родителей и ребенка
    }

    public function kill_aliver($id) {
        $world = new Trtr_world();
        $world->new_death();
        $dead_men = $this->where('id', $id)->first();
        $dead_men->death = 1;
        $dead_men->update();
    }

    public function live_alive() {
        $res = $this->where('death', '0')->get();
        return $res;
    }

    public function death_charter() { //выбор умирающих
        $res = false;

        $dead_men = $this
                ->where('death', 0)
                ->where('creators', '!=', 1)
                ->where('work', '<', 20)
                ->orWhere('love', '<', 20)
                ->orWhere('love', '>', 100)
                ->orWhere('live', '<', 20)
                ->orWhere('live', '>', 80)
                ->inRandomOrder()
                ->first(); //ищем кондидата на смерть

        $position = new Trtr_position();
        $names = new Trtr_name();

        if (!is_null($dead_men)) { //если нашли
            $pos_user = $position->where('alive_id', $dead_men->id)->pluck('location');

            $dcheck = $this->where('id', $dead_men->id)->where('death', '0')->first();

            if (isset($pos_user[0]) && rand(1, 3) === 3 && !is_null($dcheck)) { //чувак в лесу и готов закрыться
                if ($pos_user[0] === 4) {
                    $res = $names->get_only_name_by_id($dead_men->name_id);
                    $this->kill_aliver($dead_men->id);
                }
            }
        }

        return $res;
    }

}
