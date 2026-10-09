<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    private array $provinceMap = [];
    private array $regencyMap = [];
    private array $districtMap = [];

    public function run(): void
    {
        $path = database_path('data');

        $this->seedProvinces($path.'/provinces.json');
        $this->seedRegencies($path.'/regencies.json');
        $this->seedDistricts($path.'/districts.json');
        $this->seedVillages($path.'/villages.json');
    }

    private function data($file)
    {
        return json_decode(file_get_contents($file), true);
    }

    private function clean($value)
    {
        return trim($value);
    }

    private function seedProvinces($file)
    {
        foreach($this->data($file) as $item){
            $id = DB::table('provinces')->insertGetId([
                'code'=>$item['code'],
                'name'=>$this->clean($item['name'])
            ]);
            $this->provinceMap[$item['code']]=$id;
        }
    }

    private function seedRegencies($file)
    {
        foreach($this->data($file) as $item){
            if(!isset($this->provinceMap[$item['province_code']])) continue;

            $id = DB::table('regencies')->insertGetId([
                'code'=>$item['code'],
                'province_id'=>$this->provinceMap[$item['province_code']],
                'name'=>$this->clean($item['name'])
            ]);
            $this->regencyMap[$item['code']]=$id;
        }
    }

    private function seedDistricts($file)
    {
        foreach($this->data($file) as $item){
            if(!isset($this->regencyMap[$item['regency_code']])) continue;

            $id = DB::table('districts')->insertGetId([
                'code'=>$item['code'],
                'regency_id'=>$this->regencyMap[$item['regency_code']],
                'name'=>$this->clean($item['name'])
            ]);
            $this->districtMap[$item['code']]=$id;
        }
    }

    private function seedVillages($file)
    {
        foreach($this->data($file) as $item){
            if(!isset($this->districtMap[$item['district_code']])) continue;

            DB::table('villages')->insert([
                'code'=>$item['code'],
                'district_id'=>$this->districtMap[$item['district_code']],
                'name'=>$this->clean($item['name'])
            ]);
        }
    }
}
