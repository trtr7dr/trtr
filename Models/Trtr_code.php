<?php

    namespace App\Models;
	
    use Illuminate\Database\Eloquent\Model;

    class Trtr_code extends Model {

	protected $table = "trtr__code";
	protected $fillable = ['id', 'type', 'text'];


	/*
		Коды:
		1 - создание мира
		2 - добавление волшебной пыли на шаге
                3 - рождение нового персонажа
                4 - смерть персонажа
                5 - массовое вымирание
                6 - перенаселение мира
                7 - переизбыток денег
                8 - катаклизм. Смерть.
                9 - катаклизм. Уравнение.
                10 - катаклизм. Прирост.
                11 - катаклизм. Убийство.
                12 - новый король.
                13 - убийство нового короля.
                14 - победа революционного короля.
                15 - король казнит
                16 - король родил
                17 - смерть от старости
                18 - бог злится
                19 - бог проклинает
                20 - новый вампир
                21 - новый оборотень
                22 - укус вампира
	*/

	public function get_text($type){
		$res = $this->where('type', $type)->inRandomOrder()->limit(1)->first();
		return $res->text;
	}
	

}
    