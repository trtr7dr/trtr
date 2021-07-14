<?php

namespace App\Models;

use App\Models\Trtr_alive;
use App\Models\Trtr_world;
use App\Models\Trtr_news;
use App\Models\Trtr_name;
use Illuminate\Database\Eloquent\Model;

class Trtr_creators extends Model {

    public static $vampire = 1;
    public static $vam_new = 20;
    public static $vam_stp = 22;
    public static $werewolf = 2;
    public static $wer_new = 21;
    public static $wer_stp = 23;
    public static $cooldown = 25;
    public static $kill_chance = 10;

    public function creators_step() {
        $this->add_creators();
        $this->vam_step();
        $this->wer_step();
    }

    public function add_creators() {
        $alivers_model = new Trtr_alive();
        $names = new Trtr_name();
        $news = new Trtr_news();
        $world_model = new Trtr_world();
        $world = $world_model->where('description', '')->first();

        $code = 0;
        $check = $alivers_model
                ->where('creators', 0)
                ->where('death', 0)
                ->first(); //можно ли кого-то обратить?
        if ($world->instant % self::$cooldown === 0 && !is_null($check)) {
            $new_creators = $alivers_model
                            ->where('creators', 0)
                            ->where('death', 0)
                            ->inRandomOrder()
                            ->first();
            $new_creators->creators = rand(1, 2);
            $new_creators->update();
            if ($new_creators->creators === self::$vampire) {
                $code = self::$vam_new;
            } elseif ($new_creators->creators === self::$werewolf) {
                $code = self::$wer_new;
            }
            $news->new_creator($code, $names->get_name_by_id($new_creators->name_id));
        }
    }

    public function vam_step() {
        $alivers_model = new Trtr_alive();
        $news = new Trtr_news();
        $names = new Trtr_name();
        $d = date('G');
        if ($d == 1) {
            $vamp = $alivers_model
                    ->where('death', 0)
                    ->where('creators', self::$vampire)
                    ->inRandomOrder()
                    ->first();
            $target = $alivers_model
                    ->where('death', 0)
                    ->where('creators', 0)
                    ->inRandomOrder()
                    ->first();

            if (!is_null($vamp) && !is_null($target)) {
                $vamp->kills = $vamp->kills + 1;
                $target->creators = self::$vampire;
                $vamp->update();
                $target->update();
                $news->vamp_news($names->get_name_by_id($vamp->name_id), $names->get_name_by_id($target->name_id), self::$vam_stp);
                $news->new_creator(self::$vam_new, $names->get_name_by_id($target->name_id));
            }
        }
        $vampires = $alivers_model
                ->where('death', 0)
                ->where('creators', self::$vampire)
                ->get();
        foreach ($vampires as $el) {
            $el->live = 0;
            $el->update();
        }
    }

    public function wer_step() {
        $rnd = rand(0, self::$kill_chance);
        $alivers_model = new Trtr_alive();
        $news = new Trtr_news();
        $names = new Trtr_name();
        $d = date('G');
        if ($d === 3) {
            $wer = $alivers_model
                    ->where('death', 0)
                    ->where('creators', self::$werewolf)
                    ->inRandomOrder()
                    ->first();
            $target = $alivers_model
                    ->where('death', 0)
                    ->inRandomOrder()
                    ->first();

            if (!is_null($wer) && !is_null($target) && $rnd === 0) {
                $wer->kills = $wer->kills + 1;
                $target->creators = self::$werewolf;
                $target->death = 1;
                $n_k = $names->get_name_by_id($wer->name_id);
                $n_d = $names->get_name_by_id($target->name_id);
                $news->killer_news($n_k[0], $n_d[0]);

                $wer->update();
                $target->update();
            }
        }
        if(rand(0,25) === 0){
            $new_creators = $alivers_model->where('creators', 0)->inRandomOrder()->first();
            $new_creators->creators = self::$werewolf;
            $new_creators->update();
            $news->new_creator(self::$wer_new, $names->get_name_by_id($new_creators->name_id));
        }
    }

}
