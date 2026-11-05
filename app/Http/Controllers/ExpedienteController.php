<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    // Muestra la página principal.
    public function index()
    {
        return view('expedientes.index');
    }

    // Obtiene los expedientes aplicando búsqueda y filtros.
    public function data(Request $request)
    {
        $consulta = Expediente::query();
        //BUSACR
        //Buscamos por título o descripción.
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;

            $consulta->where(function ($query) use ($buscar) {
                $query->where('titulo', 'ILIKE', "%{$buscar}%")
                      ->orWhere('descripcion', 'ILIKE', "%{$buscar}%");
            });
        }
        // Filtramos por estado y categoria 
        if ($request->filled('estado')) {
            $consulta->where('estado', $request->estado);
        }

        if ($request->filled('categoria')) {
            $consulta->where('categoria', $request->categoria);
        }

        //ORDENAR
        //Columnas permitidas para ordenar.
        $columnasPermitidas = ['id', 'titulo', 'fecha_registro'];

        // Recibimos la columna y dirección solicitadas.
        $ordenarPor = $request->get('ordenar_por', 'id');
        $direccion = $request->get('direccion', 'desc');

        // Verificamos que la sean válidas.
        if (!in_array($ordenarPor, $columnasPermitidas, true)) {
            $ordenarPor = 'id';
        }
        if (!in_array($direccion, ['asc', 'desc'], true)) {
            $direccion = 'desc';
        }

        // Ordenamos y paginamos los resultados.
        $expedientes = $consulta
            ->orderBy($ordenarPor, $direccion)
            ->paginate(10)
            ->withQueryString();

        return response()->json($expedientes);
    }

}