<?php

namespace Database\Seeders;
use App\Models\Expediente;
use Illuminate\Database\Seeder;

class ExpedienteSeeder extends Seeder
{
    public function run(): void
    {
        // Array con las categorías disponibles.
        $categorias = [
            'Habilitaciones',
            'Obras Privadas',
            'Comercio',
            'Administración',
            'Recursos Humanos',
        ];

        // Array con los estados posibles.
        $estados = [
            'activo',
            'pendiente',
            'archivado',
        ];

        // Bucle que se repite 80 veces.
        for ($i = 1; $i <= 80; $i++) {

            // Crea un nuevo registro en la tabla expedientes.
            Expediente::create([

                // Genera códigos consecutivos:
                // EXP-2026-00001, EXP-2026-00002, etc.
                'codigo' => 'EXP-2026-' . str_pad($i, 5, '0', STR_PAD_LEFT),

                'titulo' => fake()->randomElement([
                    'Solicitud de habilitación comercial',
                    'Pedido de autorización de obra',
                    'Presentación de documentación',
                    'Solicitud de inspección municipal',
                    'Trámite administrativo',
                    'Renovación de permiso municipal',
                ]),

                'categoria' => fake()->randomElement($categorias),
                'estado' => fake()->randomElement($estados),
                'descripcion' => fake()->paragraph(3),
                'observaciones' => fake()->optional()->paragraph(),

                // Genera una fecha aleatoria entre hace 3 años y hoy.
                'fecha_registro' => fake()->dateTimeBetween(
                    '-3 years',
                    'now'
                )->format('Y-m-d'),

            ]);

            // El bucle vuelve a comenzar hasta completar los 80 registros.
        }
    }
}