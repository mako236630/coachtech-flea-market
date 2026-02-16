<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressIdToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'address_id')) {
                $table->dropForeign(['address_id']);
                $table->dropColumn('address_id');
            }

            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_address')->nullable()->after('shipping_postcode');
            $table->string('shipping_building')->nullable()->after('shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['shipping_postal_code', 'shipping_address', 'shipping_building']);
            $table->foreignId('address_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
}
