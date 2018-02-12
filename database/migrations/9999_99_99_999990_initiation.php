<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Initiation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // initiation settings
        DB::table('settings')->insert(
            [
                'meta_key' => 'general-settings',
                'meta_value' => '{"application_name":"Thanatos Point Of Sales","currency":"Rp","dateFormat":"mm.dd.yyyy","timezone":"Asia\/Jakarta","productTax":"no","productExpiry":"no","logo_light":"\/assets\/images\/system\/logo_light.png","logo_dark":"\/assets\/images\/system\/logo_dark.png","logo_dark_small":"\/assets\/images\/system\/logo_dark_small.png","socketioHost":"127.0.0.1","socketioPort":"9090","redisHost":"127.0.0.1","redisPort":"6379","redisPass":null,"redisDb":"0","productVariant":"no","productBarcode":"DATAMATRIX","payment_instruction":"Mohon melakukan pembayaran ke nomor rekening berikut ini : \nBCA: 01919232 A.N 123123"}'
            ]
        );
        // Initiation user root 
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@indowebdeveloper.com',
            'password' => bcrypt('administrator')
        ]);
        // initiation permission
        DB::table('permissions')->insert([
            [
                'name' => 'God Mode',
                'slug' => 'god-mode',
                'description' => 'Permission to access everything',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ]
        ]);
        DB::table('roles')->insert([
            array(
                'name' => 'Root Administrator',
                'slug' => 'root-administrator',
                'description' => 'This role can do anything',
                'created_at' => date('Y-m-d H:i:s')
            )
        ]);

        DB::table('role_user')->insert([
            array(
                'role_id' => 1,
                'user_id' => 1
            )
        ]);

        DB::table('permission_role')->insert([
            array(
                'permission_id' => 1,
                'role_id' => 1
            )
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
