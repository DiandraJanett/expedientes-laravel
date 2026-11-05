<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Expedientes</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-[#0F172A]">

    <!-- Encabezado principal -->
    <header class="bg-[#D9E5E4] px-6 py-4 shadow-sm">
        <h1 class="text-lg font-bold uppercase text-[#0C4B3C]">
            Expedientes
        </h1>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-8">

        <!-- Descripción -->
        <p class="mb-6 text-sm text-gray-500">
            Administración y consulta de expedientes municipales.
        </p>

        <!-- Buscador y filtros -->
        <div class="my-6 flex flex-wrap items-center justify-between gap-4">

            <!-- Buscador -->
            <div class="relative w-full max-w-md">
                <input
                    type="text"
                    id="buscar"
                    placeholder="Buscar expediente..."
                    class="w-full rounded-full border border-gray-200 bg-white py-2 pl-10 pr-4 text-sm outline-none transition focus:border-[#16A085] focus:ring-2 focus:ring-[#16A085]/20"
                >
                <span class="absolute left-4 top-2 text-gray-400">
                    
                </span>
            </div>

            <!-- Filtros -->
            <div class="flex flex-wrap gap-3">

                <select
                    id="filtro-estado"
                    class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:border-[#16A085]"
                >
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="archivado">Archivado</option>
                </select>

                <select
                    id="filtro-categoria"
                    class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm outline-none focus:border-[#16A085]"
                >
                    <option value="">Todas las categorías</option>
                    <option value="Habilitaciones">Habilitaciones</option>
                    <option value="Obras Privadas">Obras Privadas</option>
                    <option value="Comercio">Comercio</option>
                    <option value="Administración">Administración</option>
                    <option value="Recursos Humanos">Recursos Humanos</option>
                </select>

            </div>
        </div>

        <!-- Tabla de expedientes -->
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-y-2 text-left text-sm">

                <thead>
                    <tr class="text-xs uppercase tracking-wide text-[#2F344D]">

                        <th class="px-3 py-3">
                            <button type="button" data-ordenar="id" class="inline-flex items-center gap-2 ">
                                ID
                                <svg class="h-4 w-4 text-[#0DAC86]" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 3 6 7h8l-4-4Zm0 14 4-4H6l4 4Z"/>
                                </svg>
                            </button>
                        </th>

                        <th class="px-3 py-3">
                            <button type="button" data-ordenar="titulo" class="inline-flex items-center gap-2 ">
                                Título
                                <svg class="h-4 w-4 text-[#0DAC86]" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 3 6 7h8l-4-4Zm0 14 4-4H6l4 4Z"/>
                                </svg>
                            </button>
                        </th>

                        <th class="px-3 py-3">Categoría</th>

                        <th class="px-3 py-3">
                            <button type="button" data-ordenar="fecha_registro" class="inline-flex items-center gap-2 S">
                                Fecha de registro
                                <svg class="h-4 w-4 text-[#0DAC86]" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 3 6 7h8l-4-4Zm0 14 4-4H6l4 4Z"/>
                                </svg>
                            </button>
                        </th>

                        <th class="px-3 py-3">Estado</th>
                        <th class="px-3 py-3 text-center">Acciones</th>

                    </tr>
                </thead>

                <!-- JavaScript aca agrego los expedientes -->
                <tbody id="tabla-expedientes">
                    <tr>
                        <td colspan="6" class="px-3 py-5 text-center text-gray-500">
                            Cargando expedientes...
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

        <!-- Paginación -->
        <div id="paginacion" class="mt-6 flex flex-wrap items-center justify-center gap-2">
        </div>

    </main>

   
    <!-- Modal de detalles -->
    <div
        id="modal-detalles"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <!-- Panel principal -->
        <div class="flex w-full max-w-2xl max-h-[85vh] flex-col overflow-hidden rounded-xl bg-white shadow-2xl">

            <!-- Encabezado -->
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <div>
                    <h2 class="text-lg font-bold text-[#2F344D]">
                        Detalles del expediente
                    </h2>
                    <p class="mt-1 text-xs text-gray-500">
                        Información completa del registro
                    </p>
                </div>

                <!-- Botón X -->
                <button
                    type="button"
                    id="cerrar-modal"
                    class="flex h-8 w-8 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-100 hover:text-red-600"
                    aria-label="Cerrar modal"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Contenido con scroll -->
            <div class="min-h-0 flex-1 overflow-y-auto px-6 py-4">
                <div
                    id="contenido-modal"
                    class="grid grid-cols-1 gap-2 text-sm"
                >
                </div>
            </div>

            <!-- Pie del modal -->
            <div class="flex justify-end border-t border-gray-200 px-6 py-4">
                <button
                    type="button"
                    id="cerrar-modal-footer"
                    class="rounded-lg bg-[#2F344D] px-5 py-2 text-sm font-medium text-white transition hover:bg-[#0C4B3C]"
                >
                    Cerrar
                </button>
            </div>

        </div>
    </div>


</body>
</html>