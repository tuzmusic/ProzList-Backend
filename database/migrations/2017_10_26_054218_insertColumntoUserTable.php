<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertColumntoUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->after('password');
            $table->text('address')->after('phone');
			$table->string('profile_pic')->nullable()->after('address');
            $table->string('latitude')->nullable()->after('profile_pic');
            $table->string('longitude')->nullable()->after('latitude');
            $table->string('tax_id')->nullable()->after('longitude');
			$table->string('country')->nullable()->after('tax_id');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
			$table->string('social_security_number')->nullable()->after('city');
            $table->string('driving_licence')->nullable()->after('social_security_number');
            $table->string('basic_price')->nullable()->after('driving_licence');
            $table->integer('range')->nullable()->after('basic_price');
            $table->string('company_name')->nullable()->after('range');
            $table->string('company_desc')->nullable()->after('company_name');
            $table->string('licence_type')->nullable()->after('company_desc');
            $table->string('licence_number')->nullable()->after('licence_type');
			$table->enum('role', ['Admin','Customer', 'General','Service'])->after('licence_number');
            $table->enum('status', ['Pending', 'Active','Inactive','Delete'])->after('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
