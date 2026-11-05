import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    const tabla = document.getElementById('tabla-expedientes');
    const paginacion = document.getElementById('paginacion');
    //controles de búsqueda y filtros.
    const buscador = document.getElementById('buscar');
    const filtroEstado = document.getElementById('filtro-estado');
    const filtroCategoria = document.getElementById('filtro-categoria');

    // Elementos del modal de detalles.
    const modalDetalles = document.getElementById('modal-detalles');
    const contenidoModal = document.getElementById('contenido-modal');
    const botonCerrarModal = document.getElementById('cerrar-modal');
    const botonCerrarFooter = document.getElementById('cerrar-modal-footer');

    // Guardamos el estado actual del ordenamiento.
    let columnaOrden = 'id';
    let direccionOrden = 'desc';


    //CERRAR MODAL 
    function cerrarModal() {
        modalDetalles.classList.add('hidden');
    }
    // Cerramos al presionar la X.
    botonCerrarModal.addEventListener('click', cerrarModal);
    // Cerramos al presionar el botón Cerrar.
    botonCerrarFooter.addEventListener('click', cerrarModal);
    // Cerramos al hacer clic en el fondo oscuro.
    modalDetalles.addEventListener('click', (event) => {
        if (event.target === modalDetalles) {
            cerrarModal();
        }
    });
    // Cerramos al presionar la tecla Escape.
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            cerrarModal();
        }
    });

    //BUSCAR
    // Construimos la URL con los valores de búsqueda y filtros.
    function obtenerUrl(pagina = 1) {

        const parametros = new URLSearchParams();

        // Agregamos el texto de búsqueda si se ingresó alguno.
        if (buscador.value.trim()) {
            parametros.set('buscar', buscador.value.trim());
        }

        // Agregamos el estado seleccionado.
        if (filtroEstado.value) {
            parametros.set('estado', filtroEstado.value);
        }

        // Agregamos la categoría seleccionada.
        if (filtroCategoria.value) {
            parametros.set('categoria', filtroCategoria.value);
        }

        // Indicamos qué página queremos consultar.
        parametros.set('page', pagina);

        // Agregamos la columna y dirección del ordenamiento.
        parametros.set('ordenar_por', columnaOrden);
        parametros.set('direccion', direccionOrden);

        return `/expedientes/data?${parametros.toString()}`;
    }


    //CARGA DE EXPEDIENTES
    // Cargamos los expedientes desde Laravel.
    function cargarExpedientes(url = obtenerUrl()) {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los expedientes');
                }

                // Convertimos la respuesta a JSON.
                return response.json();
            })
            .then(data => {
                // Limpiamos la tabla.
                tabla.innerHTML = '';

                
        // Verificamos si hay expedientes para mostrar o mostramos el msj.
        if (data.data.length === 0) {
            tabla.innerHTML = `
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                        No se encontraron expedientes.
                    </td>
                </tr>
            `;

        } else {
            //Mostrar los expedientes
            //Recorremos los expedientes recibidos.
            data.data.forEach(expediente => {
                const fila = document.createElement('tr');
                
                // DISEÑO: aplicamos fondo, separación y color al texto de cada fila.
                fila.className = 'bg-[#D9E5E4]/60 text-[#2F344D] text-xs hover:bg-[#D9E5E4] transition-colors';

                fila.innerHTML = `
                    <td class="rounded-l-lg px-3 py-2 font-medium">
                        ${expediente.id}
                    </td>

                    <td class="px-3 py-2 font-medium">
                        ${expediente.titulo}
                    </td>

                    <td class="px-3 py-2">
                        ${expediente.categoria}
                    </td>

                    <td class="px-3 py-2 whitespace-nowrap">
                        ${new Date(expediente.fecha_registro).toLocaleDateString('es-AR', {
                            timeZone: 'UTC'
                        })}
                    </td>

                    <td class="px-3 py-2 capitalize">
                        ${expediente.estado}
                    </td>

                    <!-- DISEÑO: botón de detalles con estilo GERPA. -->
                    <td class="rounded-r-lg px-3 py-3 text-center">
                        <button
                            type="button"
                            class="btn-detalles rounded-md bg-[#2F344D] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#0C4B3C]"
                            data-id="${expediente.id}"
                        >
                            Ver más
                        </button>
                    </td>
                `;
                //crea la tabla
                tabla.appendChild(fila);
                
                //MODAL
                // Buscamos el botón de detalles de esta fila.
                const botonDetalles = fila.querySelector('.btn-detalles');

                // Cuando hacemos clic, mostramos el expediente seleccionado.
                botonDetalles.addEventListener('click', () => {

                    // Limpiamos el contenido anterior del modal.
                    contenidoModal.innerHTML = '';

                    // Mostramos los datos del expediente.
                    const datos = [
                        ['Código interno', expediente.codigo],
                        ['Título', expediente.titulo],
                        ['Categoría', expediente.categoria],
                        ['Fecha de registro', new Date(expediente.fecha_registro).toLocaleDateString('es-AR', {
                            timeZone: 'UTC'
                        })],
                        ['Estado', expediente.estado],
                        ['Descripción', expediente.descripcion],
                        ['Observaciones', expediente.observaciones || 'Sin observaciones']
                    ];

                    //se crean los elementos para mostrar los datos
                    datos.forEach(([etiqueta, valor]) => {

                        // Creamos un contenedor para cada dato.
                        const contenedor = document.createElement('div');

                        //Nombre del campo.
                        const titulo = document.createElement('p');
                        titulo.textContent = etiqueta;
                        titulo.className = 'text-sm font-semibold text-[#2F344D]';

                        // Valor del campo.
                        const texto = document.createElement('p');
                        texto.textContent = valor;
                        texto.className = 'mt-1 text-sm text-gray-600 whitespace-pre-wrap break-words';

                        // Organizamos el nombre y el valor en columna.
                        contenedor.className = 'flex flex-col w-full py-2';
                        //Agregamos los dos párrafos al contenedor
                        contenedor.appendChild(titulo);
                        contenedor.appendChild(texto);
                        //Agregamos el contenedor al modal
                        contenidoModal.appendChild(contenedor);
                    });

                    // Abrimos el modal.
                    modalDetalles.classList.remove('hidden');
                });
            });
        }


            
        // PAGINACIÓN
        // Generamos un botón para cada número de página.
        const botonesNumeros = Array.from(
            { length: data.last_page },
            (_, indice) => {
                const numero = indice + 1;
                const paginaActiva = numero === data.current_page;

                return `
                    <button
                        type="button"
                        data-pagina="${numero}"
                        class="rounded-md px-3 py-2 text-sm font-medium transition
                            ${paginaActiva
                                ? 'bg-[#0DAC86] text-white'
                                : 'bg-[#D9E5E4]/60 text-[#2F344D] hover:bg-[#D9E5E4]'}"
                        ${paginaActiva ? 'disabled' : ''}
                    >
                        ${numero}
                    </button>
                `;
            }
        ).join('');

        // Determinamos los estilos de los botones anterior y siguiente.
        const claseControl = 'rounded-md border border-gray-200 px-3 py-2 text-sm text-[#2F344D] transition hover:bg-[#D9E5E4] disabled:cursor-not-allowed disabled:opacity-40';

        // Mostramos los controles de paginación.
        paginacion.innerHTML = `
            <button
                type="button"
                data-pagina="1"
                class="${claseControl}"
                ${data.current_page === 1 ? 'disabled' : ''}
            >
                Primero
            </button>

            <button
                type="button"
                id="pagina-anterior"
                class="${claseControl}"
                ${data.prev_page_url ? '' : 'disabled'}
            >
                Anterior
            </button>

            ${botonesNumeros}

            <button
                type="button"
                id="pagina-siguiente"
                class="${claseControl}"
                ${data.next_page_url ? '' : 'disabled'}
            >
                Siguiente
            </button>

            <button
                type="button"
                data-pagina="${data.last_page}"
                class="${claseControl}"
                ${data.current_page === data.last_page ? 'disabled' : ''}
            >
                Último
            </button>
        `;

        // Evento para cambiar a una página determinada.
        paginacion.querySelectorAll('[data-pagina]').forEach(boton => {
            boton.addEventListener('click', () => {
                const numeroPagina = Number(boton.dataset.pagina);

                cargarExpedientes(obtenerUrl(numeroPagina));
            });
        });

        // Botón para ir a la página anterior.
        document.getElementById('pagina-anterior')
            .addEventListener('click', () => {
                if (data.prev_page_url) {
                    cargarExpedientes(obtenerUrl(data.current_page - 1));
                }
            });

        // Botón para ir a la página siguiente.
        document.getElementById('pagina-siguiente')
            .addEventListener('click', () => {
                if (data.next_page_url) {
                    cargarExpedientes(obtenerUrl(data.current_page + 1));
                }
            });
            })

            .catch(error => {
                console.error(error);

                tabla.innerHTML = `
                    <tr>
                        <td colspan="6">
                            No se pudieron cargar los expedientes.
                        </td>
                    </tr>
                `;
            });
    }


    //BUSCAR
    // Temporizador para controlar la búsqueda mientras escribimos.
    
    let temporizador;
    buscador.addEventListener('input', () => {

        clearTimeout(temporizador);

        temporizador = setTimeout(() => {
            cargarExpedientes(obtenerUrl(1));
        }, 300);

    });

    // Aplicamos el filtro cuando cambia el estado.
    filtroEstado.addEventListener('change', () => {
        cargarExpedientes(obtenerUrl(1));
    });

    // Aplicamos el filtro cuando cambia la categoría.
    filtroCategoria.addEventListener('change', () => {
        cargarExpedientes(obtenerUrl(1));
    });


    // Buscamos todos los botones que permiten ordenar columnas.
    const botonesOrden = document.querySelectorAll('[data-ordenar]');

    // Escuchamos los clics en cada encabezado.
    botonesOrden.forEach(boton => {

        boton.addEventListener('click', () => {

            // Obtenemos la columna indicada en el botón.
            const columna = boton.dataset.ordenar;

            // Si volvemos a pulsar la misma columna, invertimos el orden.
            if (columnaOrden === columna) {
                direccionOrden = direccionOrden === 'asc' ? 'desc' : 'asc';
            } else {
                // Si elegimos otra columna, comenzamos en ascendente.
                columnaOrden = columna;
                direccionOrden = 'asc';
            }

            // Volvemos a cargar los resultados desde la primera página.
            cargarExpedientes(obtenerUrl(1));
        });

    });

    // Cargamos la primera página al ingresar.
    cargarExpedientes();

});