<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseSchema extends Migration
{
    protected $table = 'warehouses';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('user_id');
            $table->string('stock_id')->nullable();
            $table->integer('is_sold')->comment('賣出狀態 0:未出 1:已出')->default(0);
            $table->date('buy_date')->comment('買入日期');
            $table->float('buy_price');
            $table->date('sold_date')->comment('賣出日期')->nullable();
            $table->float('sold_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
