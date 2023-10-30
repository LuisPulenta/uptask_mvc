<div class="contenedor olvide">
    
<?php include_once __DIR__ .  '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">
            Recupera tu Acceso a UpTask
        </p>

        <?php include_once __DIR__ . "/../templates/alertas.php"; ?>
        
        <form class="formulario" method="POST" action="/olvide">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Ingrese Email..." name="email" value="">
            </div>
            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta?. Inicia Sesión...</a>
            <a href="/crear">¿Aún no tienes una cuenta?. Crea una...</a>
        </div>
    </div>
</div>