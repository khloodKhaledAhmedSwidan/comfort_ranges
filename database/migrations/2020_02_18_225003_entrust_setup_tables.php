<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('name_ar')->unique();
            $table->string('display_name')->nullable();
            $table->string('display_name_ar')->nullable();
            $table->string('description')->nullable();
            $table->string('description_ar')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('admin_role', function (Blueprint $table) {
            $table->integer('admin_id')->unsigned();
            $table->integer('role_id')->unsigned();

//            $table->foreign('user_id')->references('id')->on('users')
//                ->onUpdate('cascade')->onDelete('cascade');
//            $table->foreign('role_id')->references('id')->on('roles')
//                ->onUpdate('cascade')->onDelete('cascade');


            $table->foreign('admin_id')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');


            $table->primary(['admin_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('name_ar')->unique();
            $table->string('display_name')->nullable();
            $table->string('display_name_ar')->nullable();
            $table->string('description')->nullable();
            $table->string('description_ar')->nullable();
            $table->string('routes');
            $table->timestamps();

        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();


            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onUpdate('cascade')->onDelete('cascade');


//            $table->foreign('permission_id')->references('id')->on('permissions')
//                ->onUpdate('cascade')->onDelete('cascade');
//            $table->foreign('role_id')->references('id')->on('roles')
//                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('permission_role');
        Schema::drop('permissions');
        Schema::drop('role_user');
        Schema::drop('roles');
    }
}
