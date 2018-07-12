<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('bank_id');
            $table->date('transaction_date');
            $table->string('invoice');
            $table->string('Status',64);
            $table->string('StatusCode',64);
            $table->string('StatusMessage',256);
            $table->string('TransactionId',512);
            $table->string('CaptureState',64);
            $table->string('TransactionState',64);
            $table->decimal('Amount', 12, 2);
            $table->string('CardType', 64)->default('None');
            $table->string('ApprovalCode', 64);
            $table->string('MaskedPAN',64)->default('None');
            $table->string('PaymentAccountDataToken',512);
            $table->timestamps();
            $table->index('user_id');
            $table->index('transaction_date');
            $table->index('Status');
            $table->unique('invoice');
        });
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
