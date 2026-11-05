<?php
 /**php artisan make:model Expediente -m    
  * php artisan migrate
  */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('titulo');
            $table->string('categoria');
            $table->enum('estado', ['activo', 'pendiente', 'archivado']);
            $table->text('descripcion');
            $table->longText('observaciones')->nullable();
            $table->date('fecha_registro');
            $table->timestamps();
        });
            }

  
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
