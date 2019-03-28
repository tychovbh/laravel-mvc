<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password');
            $table->string('firstname')->nullable();
            $table->string('surname')->nullable();
            $table->boolean('hidden')->default(0);
            $table->timestamps();
        });
        $now = Carbon::now();
        DB::table('test_users')->insert([
            'email' => 'hello@orchestraplatform.com',
            'password' => Hash::make('123'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_users');
    }
}
