<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Selectores</title>
    <script>
        // Función para cargar grupos por tipo
        function cargarGrupos(tipo) {
            const baseUrl = window.location.protocol + '//' + window.location.host;
            const url = `${baseUrl}/laravel/api/grupos-por-tipo?tipo=${encodeURIComponent(tipo)}`;
            console.log('Consultando URL:', url);
            
            fetch(url)
                .then(response => {
                    console.log('Status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Datos recibidos:', data);
                    
                    // Limpiar el selector actual
                    const select = document.getElementById('grupos');
                    select.innerHTML = '<option value="">Seleccione un grupo</option>';
                    
                    // Agregar nuevas opciones
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(grupo => {
                            const option = document.createElement('option');
                            option.value = grupo.id;
                            option.textContent = grupo.descripcion;
                            select.appendChild(option);
                        });
                    } else {
                        console.log('No hay grupos disponibles');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        
        // Al cargar la página
        window.onload = function() {
            // Agregar evento al selector de tipo
            document.getElementById('tipos').addEventListener('change', function() {
                const tipo = this.value;
                if (tipo) {
                    cargarGrupos(tipo);
                }
            });
        };
    </script>
</head>
<body>
    <h1>Prueba de Selectores Dinámicos</h1>
    
    <div>
        <label for="tipos">Tipo de Cargo:</label>
        <select id="tipos">
            <option value="">Seleccione tipo</option>
            <option value="bachiller">Bachiller</option>
            <option value="tecnico_superior">Técnico Superior</option>
            <option value="profesional_universitario">Profesional Universitario</option>
        </select>
    </div>
    
    <div>
        <label for="grupos">Grupo de Cargo:</label>
        <select id="grupos">
            <option value="">Seleccione un grupo</option>
        </select>
    </div>
    
    <div id="debug" style="margin-top: 20px; border: 1px solid #ccc; padding: 10px;">
        <h3>Información de Depuración</h3>
        <div id="debug-info"></div>
    </div>
</body>
</html>
