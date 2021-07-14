<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Trtr_alive;
use App\Models\Trtr_location;
use Illuminate\Database\Eloquent\Model;

class Trtr_position extends Model {

    protected $table = "trtr__position";
    protected $fillable = ['id', 'alive_id', 'location', 'steps'];

    public function set_position() {
        
    }

    public function set_start() {
        $this->truncate();
        $alive_model = new Trtr_alive();

        $alive = $alive_model->all();
        $iid = 0;
        $pos = [];
        foreach ($alive as $el) {
            $pos['id'] = $iid;
            $iid++;
            $pos['alive_id'] = $el->id;
            $pos['location'] = rand(1, 3);
            $pos['steps'] = 1;
            $this->insert($pos);
        }
    }

    public function get_pos_by_id($id) {
        $res = $this->where('alive_id', $id)->pluck('location');
        return $res[0];
    }

    public function get_steps_by_id($id) {
        $res = $this->where('alive_id', $id)->pluck('steps');
        return $res[0];
    }

    public function get_new_dust() {

        $dust_loc = $this->where('location', '2')->get();
        $sum = 0;
        foreach ($dust_loc as $el) {
            $sum += $el['steps'] * $el['steps'];
        }
        $live_loc = $this->where('location', '1')->get();
        foreach ($live_loc as $el) {
            $sum -= 1;
        }

        return $sum;
    }

    public function new_positions() {

        $alive_model = new Trtr_alive();
        $alive = $alive_model->live_alive();
        $loc = new Trtr_location();
        $priority = '';

        foreach ($alive as $el) {
            $priority = $loc->get_id_by_code_name($alive_model->get_priority($el['id']));

            $upd_check = $this
                            ->where('alive_id', $el['id'])
                            ->where('location', $priority)->count();


            $upd_pos = $this->where('alive_id', $el['id'])->first();
            
            $upd_pos->location = $priority;
            

          
            
            if (!$upd_check) {
                $upd_pos->steps = 1;
            } else {
                $upd_pos->steps = $upd_pos->steps + 1;
            }
            $upd_pos->update();
        }
    }

}
