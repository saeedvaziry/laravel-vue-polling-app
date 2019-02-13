<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_addresses', function (Blueprint $table) {
            $table->decimal('ip_from', 10, 0);
            $table->decimal('ip_to', 10, 0);
            $table->string('country_code', 2);
            $table->string('country_name', 64);

            $table->index('ip_from');
            $table->index('ip_to');
            $table->index(['ip_from', 'ip_to']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ip_addresses');
    }
}
