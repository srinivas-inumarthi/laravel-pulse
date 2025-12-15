<?php

use Goapptiv\Pulse\Constants;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePulseEventCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pulse_event_communications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('event');
            $table->string('sms_template')->nullable();
            $table->string('email_template')->nullable();
            $table->string("whatsapp_template")->nullable();
            $table->longtext("sms_variables")->nullable();
            $table->longtext("email_variables")->nullable();
            $table->longtext('whatsapp_variables')->nullable();
            $table->enum('status', Constants::$STATUSES)->default(Constants::$ACTIVE);
            $table->timestamps();
        });
    }
}