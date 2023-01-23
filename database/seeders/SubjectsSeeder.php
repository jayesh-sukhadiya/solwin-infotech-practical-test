<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Add multiple subjects
    	$subjects = ["C","C++","PHP","Android","Software Engineering"];
    	foreach ($subjects as $key => $value) {
    		if(DB::table('subjects')->where('name',$value)->doesntExist()){
    			DB::table('subjects')->insert([
	            'name' => $value,
	        	]);
    		}
    	}
    }
}
