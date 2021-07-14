<?php

    namespace App\Models;

    use Carbon\Carbon;
    use App\Models\Trtr_alive;
    use App\Models\Trtr_position;
    use App\Models\Trtr_news;
    use App\Models\Trtr_name;
    use App\Models\Trtr_sub_name;
    use Illuminate\Database\Eloquent\Model;

    class Trtr_world extends Model {

	protected $table = "trtr__world";
	protected $fillable = ['id', 'description', 'dust', 'instant', 'death'];

	public function check_world() {
	    $gets = $this->where('description', '')->max('created_at');

	    if (is_null($gets)) {
		$this->generate_new_world();
	    }

	    $alive = Trtr_alive::all()->count();
	    $pos = Trtr_position::all()->count();
	    if ($alive === 0) {
		$gets = $this->where('description', '')->first();
		$gets->delete();
		$this->generate_new_world();
	    }
	}
        

        private function generate_new_world() {

	    //создать новый мир
	    $new_world = [];
	    $new_world['id'] = $this->max('id') + 1;
	    $new_world['created_at'] = Carbon::now()->toDateTimeString();
	    $this->insert($new_world);
	    //создать новый мир

	    $alives = new Trtr_alive();
	    $alives->new_world_alive();

	    $position = new Trtr_position();
	    $position->set_start();

	    $news = new Trtr_news();
	    $news->new_world_gen($new_world['id']);
	}

	public function check_end() {
	    $this->check_alivers();
	    $this->check_dust();
	}

	private function check_alivers() {
	    $alive_model = new Trtr_alive();
	    $alivers_count = $alive_model->where('death', '0')->count();
	    $world_id = $this->max('id');
	    if ($alivers_count < 1) {
		$this->drop_world($world_id, 5);
	    }
	    if ($alivers_count > 100) {
		$this->drop_world($world_id, 6);
	    }
	}

	private function check_dust() {
	    $world_id = $this->max('id');
	    $dust = $this->where('id', $world_id)->pluck('dust');
	    if ($dust[0] > 100000 || $dust[0] < -100000) {
		$this->drop_world($world_id, 7);
	    }
	}

	private function drop_world($wid, $code) {
	    $world_id = $this->max('id');
	    $world = $this->where('id', $world_id)->first();
	    $news = new Trtr_news();
	    $news->end_world($wid, $code);
	    $world->description = $code;
	    $world->update();
	    Trtr_alive::truncate();
	    Trtr_position::truncate();
	}

	public function cataclysm() {
	    $ver = rand(0, 30);
	    if ($ver === 0) {
		$do = rand(0, 3);
		if ($do === 0) {
		    $this->cata_kill();
		}
		if ($do === 1) {
		    $this->cata_live();
		}
		if ($do === 2) {
		    $this->cata_res();
		}
	    }
	    $kil = rand(0, 35);
	    if ($kil === 1) {
		$this->killers();
	    }
	}

	private function cata_kill() {
	    $alive = new Trtr_alive();
	    $pos = new Trtr_position();

	    $alivers_count = $alive->count();

	    $dead = $alive
                    ->inRandomOrder()
                    ->limit(rand(0, $alivers_count))
                    ->where('creators', '!=', 1)
                    ->get();

	    foreach ($dead as $el) {
                $this->new_death();
		$d = $alive->where('id', $el['id'])->first();
		$p = $pos->where('alive_id', $el['id'])->first();
		$d->delete();
		$p->delete();
	    }
	    $news = new Trtr_news();
	    $news->cata_news(8);
	}

	private function cata_live() {
	    $alive = new Trtr_alive();

	    $r_new = rand(1, 10);
	    for ($i = 0; $i < $r_new; $i++) {
		$alive->new_aliver(null);
	    }
	    $news = new Trtr_news();
	    $news->cata_news(9);
	}

	private function cata_res() {
	    $alivers_model = new Trtr_alive();
	    $alivers = $alivers_model->where('death', 1)->limit(rand(1, 10))->get();
	    foreach ($alivers as $el) {
		$el->death = 0;
		$el->update();
	    }
	    $news = new Trtr_news();
	    $news->cata_news(10);
	}

	private function killers() {
	    $alivers_model = new Trtr_alive();
	    $names = new Trtr_name();
	    $killer = $alivers_model
		    ->where('death', 0)
		    ->inRandomOrder()
		    ->first();
	    $dier = $alivers_model
		    ->where('death', 0)
		    ->where('id', '!=', $killer->id)
		    ->inRandomOrder()
		    ->first();
	    $killer->kills = $killer->kills + 1;
            if($dier->king === 1){
                $sub = new Trtr_sub_name();
		$sname = $sub->get_random_name(9);
		$killer->nick_name = $killer->nick_name . ' ' . $sname->name;
            }
	    if ($killer->kills > 3) { //наубивал на звание
		$sub = new Trtr_sub_name();
		$sname = $sub->get_random_name(1);
		$killer->nick_name = $killer->nick_name . ' ' . $sname->name;
	    }
	    $killer->update();
	    $dier->death = 1;
            $this->new_death();
	    $dier->update();
	    $news = new Trtr_news();
	    $n_k = $names->get_name_by_id($killer->name_id);
	    $n_d = $names->get_name_by_id($dier->name_id);
	    $news->killer_news($n_k[0], $n_d[0]);
	}
        
        public function new_death(){
            $world_upd = $this->where('description', '')->first();
            $world_upd->death = $world_upd->death + 1;
            $world_upd->update();
        }
        
    }
    