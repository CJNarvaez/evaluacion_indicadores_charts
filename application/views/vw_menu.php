<style type="text/css">
<!--
body 
{
background-position: 0px 50px;
}
-->
</style>
<!-- Static navbar -->
      <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Evaluación</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li <?php if($selec == 'principal') echo 'class="active"' ?> ><a href="<?php echo site_url('/principal') ?>"><span class="glyphicon glyphicon-list"></span> Menú Principal</a></li>
              <li <?php if($selec == 'captura') echo 'class="active"' ?>><a href="<?php echo site_url('/principal/captura') ?>"><span class="glyphicon glyphicon-pencil"></span> Captura</a></li>
              <li <?php if($selec == 'reporte') echo 'class="active"' ?>><a href="<?php echo site_url('/principal/reporte') ?>"><span class="glyphicon glyphicon-list-alt"></span> Reportes</a></li>
              <li <?php if($selec == 'ind_hosp') echo 'class="active"' ?>><a href="<?php echo site_url('/principal/reporte_ind_hosp_form') ?>"><span class="glyphicon glyphicon-stats"></span> Ind. Hospitalarios</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="<?php echo site_url('/auth/logout') ?>">Salir</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>
