<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting')->insert([
        	'id_setting' => 1,
        	'nama_perusahaan' => 'Lunatic',
        	'alamat' => 'Jl. Jakarta - Bogor, Serua, Depok',
        	'telepon' => '082265457823',
            'diskon' => 5,
        	'tipe_nota' => 1,
        	'path_logo' => '/img/logo.png',
        	'path_kartu_member' => '/img/member.jpg',
        ]);
    }
}
