<?php
namespace Awcode\ThaiLaravel\Helpers;


class ThaiIdentityCard
{
    protected $raw_value;
    protected $value;
    protected $validated = false;

    protected $is_national;
    protected $is_migrant;//can we separate this??
    protected $is_foreigner;
    
    protected $min_birth_year_int;
    protected $max_birth_year_int;
    protected $oldest_recorded_lifespan = 123;

    public function __toString()
    {
        try {
            return (string) $this->value;
        }
        catch (Exception $exception) {
            return '';
        }
    }

    static function makeCard($id_number){
        $id = new ThaiIdentityCard;
        $id->raw_value = $id_number;

        $id_number = preg_replace('/[^0-9]/i', '', $id_number);
        $id->value = $id_number;

        return $id;
    }


    public function validate(){
        $id = $this->value;
        if(!$id){return false;}

        if(!$this->checkLength($id)){ return false;}
        if(!$this->checkFirstDigit($id)){ return false;}
        if(!$this->check23Digits($id)){ return false;}
        if(!$this->check45Digits($id)){ return false;}//TODO, config for this to be softfail, new options may become available later
        if(!$this->checksum($id)){ return false;}

        $this->validated = true;
        return true;
    }

    protected function checkLength($id){
        if(strlen($id) == 13){
            return true;
        }
        return false;
    }
    protected function checkFirstDigit($id){
        $digit = $id[0];

        if($digit == 1){//1: Thai nationals born after Jan 1st 1984
          $this->is_national = true;
          $this->min_birth_year_int = 1984;
          $this->max_birth_year_int = date('Y');
          return true;
        }elseif($digit == 2){//2: Thai nationals born after Jan 1st 1984, late registered
          $this->is_national = true;
          $this->min_birth_year_int = 1984;
          $this->max_birth_year_int = date('Y');
          return true;
        }elseif($digit == 3){//3: Thai nationals registered in May 31 1981 census
          $this->is_national = true;
          $this->min_birth_year_int = 1984 - $this->oldest_recorded_lifespan;
          $this->max_birth_year_int = 1984;
          return true;
        }elseif($digit == 4){//4: Thai nationals registered in May 31 1981 census, without previous id number
          $this->is_national = true;
          $this->min_birth_year_int = 1984 - $this->oldest_recorded_lifespan;
          $this->max_birth_year_int = 1984;
          return true;
        }elseif($digit == 5){//5: Thai nationals added to 1981 census late due to error
          $this->is_national = true;
          $this->min_birth_year_int = 1984 - $this->oldest_recorded_lifespan;
          $this->max_birth_year_int = 1984;
          return true;
        }elseif($digit == 6){//6: Foreigners with intent to stay temporarily, or who entered illegaly
          $this->is_foreigner = true;
          $this->min_birth_year_int = 1984 - $this->oldest_recorded_lifespan;
          $this->max_birth_year_int = date('Y');
          return true;
        }elseif($digit == 7){//7: Children of #6
          $this->is_foreigner = true;
          $this->min_birth_year_int = 1984 - $this->oldest_recorded_lifespan;
          $this->max_birth_year_int = date('Y');
          return true;
        }elseif($digit == 8){//8: Former aliens converted to Thai after 1984
          $this->is_national = true;
          $this->min_birth_year_int = 1984 - $this->oldest_recorded_lifespan;
          $this->max_birth_year_int = date('Y');
          return true;
        }elseif($digit == 0){
            //TODO - needs review, Not found on cards of Thai nationals but may be found in the other issued identity cards. File an issue on github with examples if come across cards starting with a 0
            return false;
        }
        return false;
    }
    protected function check23Digits($id){
        //digits 23 refer to province at time of applying for ID card
        $digits23 = $id[1].$id[2];

        $allowed = [
          10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
          20, 21, 22, 23, 24, 25, 26, 27,
          30, 31, 32, 33, 34, 35, 36, 37, 38, 39,
          40, 41, 42, 43, 44, 45, 46, 47, 48, 49,
          50, 51, 52, 53, 54, 55, 56, 57, 58,
          60, 61, 62, 63, 64, 65, 66, 67,
          70, 71, 72, 73, 74, 75, 76, 77,
          80, 81, 82, 83, 84, 85, 86,
          90, 91, 92, 93, 94, 95, 96];
        if(in_array($digits23, $allowed)){
          return true;
        }
        return false;
    }
    protected function check45Digits($id){
        //digits 45 refer to amphur at time of applying for ID card
        $digits23 = $id[1].$id[2];
        $digits45 = $id[3].$id[4];
        if($digits45 == 99){return true;}
        elseif($digits23 == 10 && $digits45 <= 50){return true;}
        elseif($digits23 == 11 && $digits45 <= 6){return true;}
        elseif($digits23 == 12 && $digits45 <= 6){return true;}
        elseif($digits23 == 13 && $digits45 <= 7){return true;}
        elseif($digits23 == 14 && $digits45 <= 46){return true;}
        elseif($digits23 == 15 && $digits45 <= 7){return true;}
        elseif($digits23 == 16 && $digits45 <= 11){return true;}
        elseif($digits23 == 17 && $digits45 <= 9){return true;}
        elseif($digits23 == 18 && $digits45 <= 8){return true;}
        elseif($digits23 == 19 && $digits45 <= 12){return true;}
        elseif($digits23 == 20 && $digits45 <= 11){return true;}
        elseif($digits23 == 21 && $digits45 <= 8){return true;}
        elseif($digits23 == 22 && $digits45 <= 10){return true;}
        elseif($digits23 == 23 && $digits45 <= 7){return true;}
        elseif($digits23 == 24 && $digits45 <= 11){return true;}
        elseif($digits23 == 25 && $digits45 <= 9){return true;}
        elseif($digits23 == 26 && $digits45 <= 4){return true;}
        elseif($digits23 == 27 && $digits45 <= 9){return true;}
        elseif($digits23 == 30 && $digits45 <= 32){return true;}
        elseif($digits23 == 31 && $digits45 <= 23){return true;}
        elseif($digits23 == 32 && $digits45 <= 17){return true;}
        elseif($digits23 == 33 && $digits45 <= 22){return true;}
        elseif($digits23 == 34 && $digits45 <= 25){return true;}
        elseif($digits23 == 35 && $digits45 <= 9){return true;}
        elseif($digits23 == 36 && $digits45 <= 16){return true;}
        elseif($digits23 == 37 && $digits45 <= 6){return true;}
        elseif($digits23 == 38 && $digits45 <= 8){return true;}
        elseif($digits23 == 39 && $digits45 <= 6){return true;}
        elseif($digits23 == 40 && $digits45 <= 26){return true;}
        elseif($digits23 == 41 && $digits45 <= 25){return true;}
        elseif($digits23 == 42 && $digits45 <= 14){return true;}
        elseif($digits23 == 43 && $digits45 <= 9){return true;}
        elseif($digits23 == 44 && ($digits45 <= 13 || $digits45 ==95)){return true;}//check this?
        elseif($digits23 == 45 && $digits45 <= 20){return true;}
        elseif($digits23 == 46 && $digits45 <= 18){return true;}
        elseif($digits23 == 47 && $digits45 <= 18){return true;}
        elseif($digits23 == 48 && $digits45 <= 12){return true;}
        elseif($digits23 == 49 && $digits45 <= 7){return true;}
        elseif($digits23 == 50 && $digits45 <= 25){return true;}
        elseif($digits23 == 51 && $digits45 <= 8){return true;}
        elseif($digits23 == 52 && $digits45 <= 12){return true;}
        elseif($digits23 == 53 && $digits45 <= 9){return true;}
        elseif($digits23 == 54 && $digits45 <= 16){return true;}
        elseif($digits23 == 55 && $digits45 <= 15){return true;}
        elseif($digits23 == 56 && $digits45 <= 9){return true;}
        elseif($digits23 == 57 && $digits45 <= 18){return true;}
        elseif($digits23 == 58 && $digits45 <= 7){return true;}
        elseif($digits23 == 60 && $digits45 <= 15){return true;}
        elseif($digits23 == 61 && $digits45 <= 8){return true;}
        elseif($digits23 == 62 && $digits45 <= 11){return true;}
        elseif($digits23 == 63 && $digits45 <= 9){return true;}
        elseif($digits23 == 64 && $digits45 <= 9){return true;}
        elseif($digits23 == 65 && $digits45 <= 22){return true;}
        elseif($digits23 == 66 && $digits45 <= 13){return true;}
        elseif($digits23 == 67 && $digits45 <= 13){return true;}
        elseif($digits23 == 70 && $digits45 <= 10){return true;}
        elseif($digits23 == 71 && $digits45 <= 13){return true;}
        elseif($digits23 == 72 && $digits45 <= 10){return true;}
        elseif($digits23 == 73 && $digits45 <= 7){return true;}
        elseif($digits23 == 74 && $digits45 <= 3){return true;}
        elseif($digits23 == 75 && $digits45 <= 3){return true;}
        elseif($digits23 == 76 && $digits45 <= 7){return true;}
        elseif($digits23 == 77 && $digits45 <= 8){return true;}
        elseif($digits23 == 80 && $digits45 <= 23){return true;}
        elseif($digits23 == 81 && $digits45 <= 8){return true;}
        elseif($digits23 == 82 && $digits45 <= 8){return true;}
        elseif($digits23 == 83 && $digits45 <= 3){return true;}
        elseif($digits23 == 84 && $digits45 <= 19){return true;}
        elseif($digits23 == 85 && $digits45 <= 4){return true;}
        elseif($digits23 == 86 && $digits45 <= 8){return true;}
        elseif($digits23 == 90 && $digits45 <= 16){return true;}
        elseif($digits23 == 91 && $digits45 <= 7){return true;}
        elseif($digits23 == 92 && $digits45 <= 10){return true;}
        elseif($digits23 == 93 && $digits45 <= 11){return true;}
        elseif($digits23 == 94 && $digits45 <= 11){return true;}
        elseif($digits23 == 95 && $digits45 <= 8){return true;}
        elseif($digits23 == 96 && $digits45 <= 13){return true;}

        return false;
    }
    protected function checksum($id){
        $checksum = $id[12];
        $add = $id[0] * 13;
        $add += $id[1] * 12;
        $add += $id[2] * 11;
        $add += $id[3] * 10;
        $add += $id[4] * 9;
        $add += $id[5] * 8;
        $add += $id[6] * 7;
        $add += $id[7] * 6;
        $add += $id[8] * 5;
        $add += $id[9] * 4;
        $add += $id[10] * 3;
        $add += $id[11] * 2;

        $mod = $add % 11;

        $diff = 11 - $mod;

        $check = $diff % 10;

        if($checksum == $check){
          return true;
        }
        return false;
    }

    public function isThaiNational(){
        if(!$this->validated){$this->validate();}
        return $this->is_national ? true : false;
    }
    public function isMigrant(){
        if(!$this->validated){$this->validate();}
        return $this->is_migrant ? true : false;
    }
    public function isForeigner(){
        if(!$this->validated){$this->validate();}
        return $this->is_foreigner ? true : false;
    }
    
    public function minBirthYearInt(){
        return $this->min_birth_year_int;
    }
    public function maxBirthYearInt(){
        return $this->max_birth_year_int;
    }
    public function minBirthYearTh(){
        return $this->minBirthYearInt() ? $this->minBirthYearInt() + 543 : null;
    }
    public function maxBirthYearTh(){
        return $this->maxBirthYearInt() ? $this->maxBirthYearInt() + 543 : null;
    }
    
    
}
