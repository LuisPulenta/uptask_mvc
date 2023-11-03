<?php include_once __DIR__.'/header-dashboard.php';?>

<div class="contenedor-sm">
    <?php include_once __DIR__.'/../templates/alertas.php';?>   

    <a href="/perfil" class="enlace">Volver al perfil</a>
  
    <form class ="formulario" method="POST" action="/cambiar-password">
        <div class="campo">
            <label for="password_actual">Password actual</label>
            <input type="password" id="password_actual" placeholder="Ingrese password actual..." name="password_actual" />
        </div>
        
        <div class="campo">
            <label for="password_nuevo">Password nuevo</label>
            <input type="password" id="password_nuevo" placeholder="Ingrese password nuevo..." name="password_nuevo" />
        </div>
        
        <input type="submit"  value="Guardar nuevo password">
    </form>

    

</div>


<?php include_once __DIR__.'/footer-dashboard.php';?>

