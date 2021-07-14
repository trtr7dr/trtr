<?php

namespace App\Models;

use App\Models\Trtr_alive;
use App\Models\Trtr_world;
use App\Models\Trtr_news;
use App\Models\Trtr_name;
use App\Models\Trtr_sub_name;
use Illuminate\Database\Eloquent\Model;

class Trtr_king extends Model {

    public static $cooldown = 100;
    public static $step_chance = 15;
    
    public function king_step() {
        $alivers_model = new Trtr_alive();
        $this->king_select();

        $king = $alivers_model->where('death', 0)->where('king', 1)->first();

        if (!is_null($king)) {
            $this->king_kill();
            $this->king_alive();
        }
    }

    private function king_alive() {
        $rnd = rand(0, self::$step_chance);

        $alivers_model = new Trtr_alive();
        $news = new Trtr_news();

        if ($rnd === 1) {
            $rod = rand(0, 4);
            if ($rod === 0) {
                $rod = 'Королевских кровей';
            } else {
                $rod = null;
            }
            $alivers_model->new_aliver($rod, 0);
            //$news->simple_news(16);
        }
    }

    private function king_kill() {
        $rnd = rand(0, self::$step_chance);
        $alivers_model = new Trtr_alive();
        $news = new Trtr_news();
        $sub = new Trtr_sub_name();
        $names = new Trtr_name();

        $king = $alivers_model
                ->where('death', 0)
                ->where('king', 1)
                ->first();
        $alivers_count = $alivers_model->where('death', 0)->count();
        if ($rnd === 1 && $alivers_count > 3) {
            $dier = $alivers_model
                    ->where('death', 0)
                    ->where('king', 0)
                    ->inRandomOrder()
                    ->first();
            $king->kills = $king->kills + 1;
            if ($king->kills > 10) {
                $sname = $sub->get_random_name(8);
                $king->nick_name = $sname->name;
            }
            $dier->death = 1;
            $news->king_killer($names->get_name_by_id($dier->name_id), $names->get_name_by_id($king->name_id));
            $dier->update();
            $king->update();
        }
    }

    private function king_select() {
        $world_model = new Trtr_world();
        $world = $world_model->where('description', '')->first();
        $names = new Trtr_name();
        $alivers_model = new Trtr_alive();
        $news = new Trtr_news();
        $sub = new Trtr_sub_name();

        $old_king = $alivers_model
                    ->where('death', 0)
                    ->where('king', 1)
                    ->first();
        if ($world->instant % self::$cooldown === 0) { //если пришло время выборов
            if (count($old_king) === 0) { //все гладко, короля еще нет
                $king = $alivers_model
                        ->where('death', 0)
                        ->where('sub_name', 'Королевских кровей')
                        ->inRandomOrder()
                        ->first();
                if (is_null($king)) {
                    $king = $alivers_model
                            ->where('death', 0)
                            ->inRandomOrder()
                            ->first();
                }

                $king->king = 1;
                $king->update();
                $news->new_king_news($names->get_name_by_id($king->name_id));
            } else { //битва за престол
                $rnd = rand(1, 3);
                if ($rnd === 1) { //победа революции
                    $alivers_count = $alivers_model->count();
                    $dead = $alivers_model
                            ->where('death', 0)
                            ->inRandomOrder()
                            ->limit(rand(0, $alivers_count))
                            ->get();

                    $old_kings = $alivers_model
                                ->where('death', 0)
                                ->where('king', 1)
                                ->get();

                    $old_child = $alivers_model->where('sub_name', 'Королевских кровей')->get();
                    foreach ($old_child as $el) {
                        $el->death = 1;
                        $el->sub_name = 'Потомок мертвого короля';
                        $el->update();
                    }

                    foreach ($old_kings as $el) {
                        $alivers_model->kill_aliver($el['id']);
                    }
                    foreach ($dead as $el) {
                        $alivers_model->kill_aliver($el['id']);
                    }
                    $king = $alivers_model
                            ->where('death', 0)
                            ->where('king', 0)
                            ->inRandomOrder()
                            ->first();
                    $king->king = 1;
                    $king->nick_name = $sub->get_random_name(7);
                    $king->update();
                    $news->king_news(14, $names->get_name_by_id($king->name_id));
                } else { //победа старой власти
                    $king = $alivers_model
                            ->where('death', 0)
                            ->where('king', 0)
                            ->inRandomOrder()
                            ->first();
                    $king->death = 1;
                    $king->nick_name = $sub->get_random_name(6);
                    $king->update();
                    $news->king_news(13, $names->get_name_by_id($king->name_id));
                }
            }
        }
    }
}
