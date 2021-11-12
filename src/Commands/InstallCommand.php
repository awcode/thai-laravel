<?php

namespace Awcode\ThaiLaravel\Commands;

use Awcode\ThaiLaravel\Models\District;
use Awcode\ThaiLaravel\Models\Region;
use Awcode\ThaiLaravel\Models\Province;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thailaravel:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install or update province and postcode data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!Region::count()){
            $data = $this->getRegionData();
            Region::insert($data);
        }
        if(!Province::count()){
            $this->getProvinceDataCSV();
            $this->getLatLngDataCSV();
            //$data = $this->getProvinceData();
            //Province::insert($data);
        }
        /*if(!District::count()){
            $data = $this->getPostcodeData();
            District::insert($data);
        }*/
        return 0;
    }
    
    function getPostcodeData(){
        $filename = dirname(__FILE__).'/../../database/thaiamphur.csv';

        $header = NULL;
        $inc = 1;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE)
            {
                if(!$header){
                    $header = $row;
                }else{
                    $data[] = [
                        'id' => $inc,
                        'district_name_th' => '',
                        'district_name_eng' => $row[0],
                        'postcode' => $row[7],
                        'latlng' => $row[3].','.$row[4],
                        'province_id' => $row[8],
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    DB::table('provinces')->where('id','=',$row[8])
                        ->update([
                            'postcode_prefix' => substr($row[7],0,2)
                        ]);
                }

                $inc++;
            }
            fclose($handle);
        }
        return $data;
    }

    function getRegionData(){
        return [
                [
                    'id'    => 1,
                    'region_name' => 'Central Zone',
                ],
                [
                    'id'    => 2,
                    'region_name' => 'Eastern zone',
                ],
                [
                    'id'    => 3,
                    'region_name' => 'Northeastern part 1',
                ],
                [
                    'id'    => 4,
                    'region_name' => 'Northeastern part 2',
                ],
                [
                    'id'    => 5,
                    'region_name' => 'Upper Northern zone',
                ],
                [
                    'id'    => 6,
                    'region_name' => 'Lower Northern zone',
                ],
                [
                    'id'    => 7,
                    'region_name' => 'Lower Central zone',
                ],
                [
                    'id'    => 8,
                    'region_name' => 'Southern zone',
                ],
                [
                    'id'    => 9,
                    'region_name' => 'Southern border zone',
                ]
            ];
    }

    function getLatLngDataCSV(){
        $filename = dirname(__FILE__).'/../../database/latlng_tambon.csv';

        $header = NULL;
        $inc = 1;
        $data = array();

        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 8000, ',')) !== FALSE)
            {
                if(!$header){
                    $header = $row;
                }else{

                    $tambon_id = $row[0];
                    if($row[1] != '' && $row[2] != ''){
                        $latlng = $row[1].','.$row[2];
                    }else{
                        $latlng = '';
                    }

                    if($latlng != ''){
                        $qTambon = DB::table('sub_districts')->where('id','=',$tambon_id);
                        if($qTambon->count() > 0){
                            DB::table('sub_districts')->where('id','=',$tambon_id)
                                ->update([
                                    'lat' => $row[1],
                                    'lng' => $row[2]
                                ]);
                        }
                    }
                }
                $inc++;
            }
            fclose($handle);
        }
        return $data;
    }

    function getProvinceDataCSV(){
        $filename = dirname(__FILE__).'/../../database/tambon-thailand.csv';

        $header = NULL;
        $inc = 1;
        $data = array();

        $region_arr = [
            'Central Zone' => 1,
            'Eastern zone' => 2,
            'Northeastern part 1' => 3,
            'Northeastern part 2' => 4,
            'Upper Northern zone' => 5,
            'Lower Northern zone' => 6,
            'Lower Central zone' => 7,
            'Southern zone' => 8,
            'Southern border zone' => 9
        ];

        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 7500, ',')) !== FALSE)
            {
                if(!$header){
                    $header = $row;
                }else{

                    $zone = $row[11];

                    $province_id = $row[8];
                    $province_name_th = $row[9];
                    $province_name_en = $row[10];

                    $district_id = $row[5];
                    $district_name_th = $row[6];
                    $district_name_en = $row[7];

                    $district_postcode = $row[3];

                    $tambon_id = $row[0];
                    $tambon_name_th = $row[1];
                    $tambon_name_en = $row[2];

                    if($province_id != ''){
                        $qProvince = DB::table('provinces')->where('id','=',$province_id);
                        if($qProvince->count() == 0){
                            DB::table('provinces')->insert([
                                'id' => $province_id,
                                'name_th' => $province_name_th,
                                'name_eng' => $province_name_en,
                                'region_id' => $region_arr[$zone],
                                'postcode_prefix' => substr($district_postcode,0,2),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }

                    if($district_id != ''){
                        $qDistricts = DB::table('districts')->where('id','=',$district_id);
                        if($qDistricts->count() == 0){
                            DB::table('districts')->insert([
                                'id' => $district_id,
                                'district_name_th' => $district_name_th,
                                'district_name_eng' => $district_name_en,
                                'postcode' => $district_postcode,
                                'province_id' => $province_id,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }

                    if($tambon_id != ''){
                        $qTambon = DB::table('sub_districts')->where('id','=',$tambon_id);
                        if($qTambon->count() == 0){
                            DB::table('sub_districts')->insert([
                                'id' => $tambon_id,
                                'subdistrict_name_th' => $tambon_name_th,
                                'subdistrict_name_eng' => $tambon_name_en,
                                'district_id' => $district_id,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }

                }
                $inc++;
            }
            fclose($handle);
        }
        return $data;
    }

    function getProvinceData(){
        return [
                 [
                    'id'    => 10,
                    'name_eng' => 'Bangkok',
                    'name_th' => 'กรุงเทพ',
                     'region_id' => 1
                ],
                 [
                    'id'    => 9,
                    'name_eng' => 'Samut Prakan',
                     'name_th' => 'สมุทรปราการ',
                     'region_id' => 1
                ],
                 [
                    'id'    => 11,
                    'name_eng' => 'Nonthaburi',
                     'name_th' => 'นนทบุรี',
                     'region_id' => 1
                ],
                 [
                    'id'    => 12,
                    'name_eng' => 'Pathum Thani',
                     'name_th' => 'ปทุมธานี',
                     'region_id' => 1
                ],
                 [
                    'id'    => 13,
                    'name_eng' => 'Ayutthaya',
                     'name_th' => 'อยุธยา',
                     'region_id' => 1
                ],
                 [
                    'id'    => 14,
                    'name_eng' => 'Ang Thong',
                     'name_th' => 'อ่างทอง',
                     'region_id' => 1
                ],
                 [
                    'id'    => 15,
                    'name_eng' => 'Lop Buri',
                     'name_th' => 'ลพบุรี',
                     'region_id' => 1
                ],
                 [
                    'id'    => 16,
                    'name_eng' => 'Sing Buri',
                     'name_th' => 'สิงห์บุรี',
                     'region_id' => 1
                ],
                 [
                    'id'    => 17,
                    'name_eng' => 'Chai Nat',
                     'name_th' => 'ชัยนาท',
                     'region_id' => 1
                ],
                 [
                    'id'    => 18,
                    'name_eng' => 'Saraburi',
                     'name_th' => 'สระบุรี',
                     'region_id' => 1
                ],
                 [
                    'id'    => 20,
                    'name_eng' => 'Chonburi',
                     'name_th' => 'ชลบุรี',
                     'region_id' => 2
                ],
                 [
                    'id'    => 21,
                    'name_eng' => 'Rayong',
                     'name_th' => 'ระยอง',
                     'region_id' => 2
                ],
                 [
                    'id'    => 22,
                    'name_eng' => 'Chanthaburi',
                     'name_th' => 'จันทบุรี',
                     'region_id' => 2
                ],
                 [
                    'id'    => 23,
                    'name_eng' => 'Trat',
                     'name_th' => 'ตราด',
                     'region_id' => 2
                ],
                 [
                    'id'    => 24,
                    'name_eng' => 'Chachoengsao',
                     'name_th' => 'ฉะเชิงเทรา',
                     'region_id' => 2
                ],
                 [
                    'id'    => 25,
                    'name_eng' => 'Prachin Buri',
                     'name_th' => 'ปราจีนบุรี',
                     'region_id' => 2
                ],
                 [
                    'id'    => 26,
                    'name_eng' => 'Nakhon Nayok',
                     'name_th' => 'นครนายก',
                     'region_id' => 2
                ],
                 [
                    'id'    => 27,
                    'name_eng' => 'Sa Kaeo',
                     'name_th' => 'สระแก้ว',
                     'region_id' => 2
                ],
                 [
                    'id'    => 30,
                    'name_eng' => 'Nakhon Ratchasima',
                     'name_th' => 'นครราชสีมา',
                     'region_id' => 3
                ],
                 [
                    'id'    => 31,
                    'name_eng' => 'Buriram',
                     'name_th' => 'บุรีรัมย์',
                     'region_id' => 3
                ],
                 [
                    'id'    => 32,
                    'name_eng' => 'Surin',
                     'name_th' => 'สุรินทร์',
                     'region_id' => 3
                ],
                 [
                    'id'    => 33,
                    'name_eng' => 'Sisaket',
                     'name_th' => 'ศรีสะเกษ',
                     'region_id' => 3
                ],
                 [
                    'id'    => 34,
                    'name_eng' => 'Ubon Ratchathani',
                     'name_th' => 'อุบลราชธานี',
                     'region_id' => 3
                ],
                 [
                    'id'    => 35,
                    'name_eng' => 'Yasothon',
                     'name_th' => 'ยโสธร',
                     'region_id' => 3
                ],
                 [
                    'id'    => 36,
                    'name_eng' => 'Chaiyaphum',
                     'name_th' => 'ชัยภูมิ',
                     'region_id' => 3
                ],
                 [
                    'id'    => 37,
                    'name_eng' => 'Amnat Charoen',
                     'name_th' => 'อำนาจเจริญ',
                     'region_id' => 3
                ],
                 [
                    'id'    => 38,
                    'name_eng' => 'Bueng Kan',
                     'name_th' => 'บึงกาฬ',
                     'region_id' => 3
                ],
                 [
                    'id'    => 39,
                    'name_eng' => 'Nong Bua Lamphu',
                     'name_th' => 'หนองบัวลําภู',
                     'region_id' => 3
                ],
                 [
                    'id'    => 40,
                    'name_eng' => 'Khon Kaen',
                     'name_th' => 'ขอนแก่น',
                     'region_id' => 4
                ],
                 [
                    'id'    => 41,
                    'name_eng' => 'Udon Thani',
                     'name_th' => 'อุดรธานี',
                     'region_id' => 4
                ],
                 [
                    'id'    => 42,
                    'name_eng' => 'Loei',
                     'name_th' => 'เลย',
                     'region_id' => 4
                ],
                 [
                    'id'    => 43,
                    'name_eng' => 'Nong Khai',
                     'name_th' => 'หนองคาย',
                     'region_id' => 4
                ],
                 [
                    'id'    => 44,
                    'name_eng' => 'Maha Sarakham',
                     'name_th' => 'มหาสารคาม',
                     'region_id' => 4
                ],
                 [
                    'id'    => 45,
                    'name_eng' => 'Roi Et',
                     'name_th' => 'ร้อยเอ็ด',
                     'region_id' => 4
                ],
                 [
                    'id'    => 46,
                    'name_eng' => 'Kalasin',
                     'name_th' => 'กาฬสินธุ์',
                     'region_id' => 4
                ],
                 [
                    'id'    => 47,
                    'name_eng' => 'Sakon Nakhon',
                     'name_th' => 'สกลนคร',
                     'region_id' => 4
                ],
                 [
                    'id'    => 48,
                    'name_eng' => 'Nakhon Phanom',
                     'name_th' => 'นครพนม',
                     'region_id' => 4
                ],
                 [
                    'id'    => 49,
                    'name_eng' => 'Mukdahan',
                     'name_th' => 'มุกดาหาร',
                     'region_id' => 4
                ],
                 [
                    'id'    => 50,
                    'name_eng' => 'Chiang Mai',
                     'name_th' => 'เชียงใหม่',
                     'region_id' => 5
                ],
                 [
                    'id'    => 51,
                    'name_eng' => 'Lamphun',
                     'name_th' => 'ลําพูน',
                     'region_id' => 5
                ],
                 [
                    'id'    => 52,
                    'name_eng' => 'Lampang',
                     'name_th' => 'ลำปาง',
                     'region_id' => 5
                ],
                 [
                    'id'    => 53,
                    'name_eng' => 'Uttaradit',
                     'name_th' => 'อุตรดิตถ์',
                     'region_id' => 5
                ],
                 [
                    'id'    => 54,
                    'name_eng' => 'Phrae',
                     'name_th' => 'แพร่',
                     'region_id' => 5
                ],
                 [
                    'id'    => 55,
                    'name_eng' => 'Nan',
                     'name_th' => 'น่าน',
                     'region_id' => 5
                ],
                 [
                    'id'    => 56,
                    'name_eng' => 'Phayao',
                     'name_th' => 'พะเยา',
                     'region_id' => 5
                ],
                 [
                    'id'    => 57,
                    'name_eng' => 'Chiang Rai',
                     'name_th' => 'เชียงราย',
                     'region_id' => 5
                ],
                 [
                    'id'    => 58,
                    'name_eng' => 'Mae Hong Son',
                     'name_th' => 'แม่ฮ่องสอน',
                     'region_id' => 5
                ],
                 [
                    'id'    => 60,
                    'name_eng' => 'Nakhon Sawan',
                     'name_th' => 'นครสวรรค์',
                     'region_id' => 6
                ],
                 [
                    'id'    => 61,
                    'name_eng' => 'Uthai Thani',
                     'name_th' => 'อุทัยธานี',
                     'region_id' => 6
                ],
                 [
                    'id'    => 62,
                    'name_eng' => 'Kamphaeng Phet',
                     'name_th' => 'กําแพงเพชร',
                     'region_id' => 6
                ],
                 [
                    'id'    => 63,
                    'name_eng' => 'Tak',
                     'name_th' => 'ตาก',
                     'region_id' => 6
                ],
                 [
                    'id'    => 64,
                    'name_eng' => 'Sukhothai',
                     'name_th' => 'สุโขทัย',
                     'region_id' => 6
                ],
                 [
                    'id'    => 65,
                    'name_eng' => 'Phitsanulok',
                     'name_th' => 'พิษณุโลก',
                     'region_id' => 6
                ],
                 [
                    'id'    => 66,
                    'name' => 'Phichit',
                     'name_eng' => 'พิจิตร',
                     'region_id' => 6
                ],
                 [
                    'id'    => 67,
                    'name_eng' => 'Phetchabun',
                     'name_th' => 'เพชรบูรณ์',
                     'region_id' => 6
                ],
                 [
                    'id'    => 70,
                    'name_eng' => 'Ratchaburi',
                     'name_th' => 'ราชบุรี',
                     'region_id' => 7
                ],
                 [
                    'id'    => 71,
                    'name_eng' => 'Kanchanaburi',
                     'name_th' => 'กาญจนบุรี',
                     'region_id' => 7
                ],
                 [
                    'id'    => 72,
                    'name_eng' => 'Suphan Buri',
                     'name_th' => 'สุพรรณบุรี',
                     'region_id' => 7
                ],
                 [
                    'id'    => 73,
                    'name_eng' => 'Nakhon Pathom',
                     'name_th' => 'นครปฐม',
                     'region_id' => 7
                ],
                 [
                    'id'    => 74,
                    'name_eng' => 'Samut Sakhon',
                     'name_th' => 'สมุทรสาคร',
                     'region_id' => 7
                ],
                 [
                    'id'    => 75,
                    'name_eng' => 'Samut Songkhram',
                     'name_th' => 'สมุทรสงคราม',
                     'region_id' => 7
                ],
                 [
                    'id'    => 76,
                    'name_eng' => 'Phetchaburi',
                     'name_th' => 'เพชรบุรี',
                     'region_id' => 7
                ],
                 [
                    'id'    => 77,
                    'name_eng' => 'Prachuap Khiri Khan',
                     'name_th' => 'ประจวบคีรีขันธ์',
                     'region_id' => 7
                ],
                 [
                    'id'    => 80,
                    'name_eng' => 'Nakhon Si Thammarat',
                     'name_th' => 'นครศรีธรรมราช',
                     'region_id' => 8
                ],
                 [
                    'id'    => 81,
                    'name_eng' => 'Krabi',
                     'name_th' => 'กระบี่',
                     'region_id' => 8
                ],
                 [
                    'id'    => 82,
                    'name_eng' => 'Phang Nga',
                     'name_th' => 'พังงา',
                     'region_id' => 8
                ],
                 [
                    'id'    => 83,
                    'name_eng' => 'Phuket',
                     'name_th' => 'ภูเก็ต',
                     'region_id' => 8
                ],
                 [
                    'id'    => 84,
                    'name_eng' => 'Surat Thani',
                     'name_th' => 'สุราษฎร์ธานี',
                     'region_id' => 8
                ],
                 [
                    'id'    => 85,
                    'name_eng' => 'Ranong',
                     'name_th' => 'ระนอง',
                     'region_id' => 8
                ],
                 [
                    'id'    => 86,
                    'name_eng' => 'Chumphon',
                     'name_th' => 'ชุมพร',
                     'region_id' => 8
                ],
                 [
                    'id'    => 90,
                    'name_eng' => 'Songkhla',
                     'name_th' => 'สงขลา',
                     'region_id' => 9
                ],
                 [
                    'id'    => 91,
                    'name_eng' => 'Satun',
                     'name_th' => 'สตูล',
                     'region_id' => 9
                ],
                 [
                    'id'    => 92,
                    'name_eng' => 'Trang',
                     'name_th' => 'ตรัง',
                     'region_id' => 9
                ],
                 [
                    'id'    => 93,
                    'name_eng' => 'Phatthalung',
                     'name_th' => 'พัทลุง',
                     'region_id' => 9
                ],
                 [
                    'id'    => 94,
                    'name_eng' => 'Pattani',
                     'name_th' => 'ปัตตานี',
                     'region_id' => 9
                ],
                 [
                    'id'    => 95,
                    'name_eng' => 'Yala',
                     'name_th' => 'ยะลา',
                     'region_id' => 9
                ],
                 [
                    'id'    => 96,
                    'name_eng' => 'Narathiwat',
                     'name_th' => 'นราธิวาส',
                     'region_id' => 9
                ]
            ];
    }
}
