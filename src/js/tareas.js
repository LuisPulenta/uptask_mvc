(function () {
    //Botón para mostrar el Modal de agregar tarea

    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    //----------------------------------------------------------------------------------------
    function mostrarFormulario() {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML =
            `
            <form class="formulario nueva-tarea">
            <legend>Añade una nueva tarea</legend>
            <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input type="text" id="tarea" placeholder="Añadir Tarea..." name="tarea"</input>
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea"/>
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>  
                
            </form>
            `
            ;
        
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function (e) {
            e.preventDefault();

            if (e.target.classList.contains('cerrar-modal')) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();   
                }, 500);                
            } 
            if (e.target.classList.contains('submit-nueva-tarea')) {
                submitFormularioNuevaTarea();
            } 

        })

        document.querySelector('.dashboard').appendChild(modal);
    }

    //----------------------------------------------------------------------------------------
    function submitFormularioNuevaTarea() {
        const tarea = document.querySelector('#tarea').value.trim();
        if (tarea === '') {
            //Mostrar Alerta de error
            mostrarAlerta('El nombre de la tarea es obligatorio','error',document.querySelector('.formulario legend'));
            return;
        }
        agregarTarea(tarea);
    }

    //----------------------------------------------------------------------------------------
    function mostrarAlerta(mensaje, tipo, referencia) {
        //Previene la creación de múltiples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) {
            alertaPrevia.remove();
        };

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);
        
        //Eliminar la alerta después de 5 segundos
        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }
    
    //----------------------------------------------------------------------------------------
    //Añadir tarea al servidor
    async function agregarTarea(tarea) { 
        //Construir la petición
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try {

            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos 
            });
            
            //console.log(respuesta);

            const resultado = await respuesta.json();

            console.log(resultado);


            
        } catch (error) {
            console.log(error);
        }

    }

    //----------------------------------------------------------------------------------------
    function obtenerProyecto() {
        const proyectoparams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoparams.entries());
        return proyecto.id;
    }

})();




