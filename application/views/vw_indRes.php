<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Indicadores de Resultados</title>

    <?php include "/inc/header_common.php" ?>

  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-header">
            <h1>Indicadores de Resultados <small>Fuente: SIS, SAEH, SUAVE(Sistemas Paralelos)</small></h1>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-10 col-xs-offset-2">
          <h3>Meses</h3>
          <div class="btn-group btn-group-justified btn-group-xs" role="group">
            <a class="btn btn-default meses" role="button" data-nombre="01">ENE</a> 
            <a class="btn btn-default meses" role="button" data-nombre="02">FEB</a> 
            <a class="btn btn-default meses" role="button" data-nombre="03">MAR</a> 
            <a class="btn btn-default meses" role="button" data-nombre="04">ABR</a>
            <a class="btn btn-default meses" role="button" data-nombre="05">MAY</a> 
            <a class="btn btn-default meses" role="button" data-nombre="06">JUN</a> 
            <a class="btn btn-default meses" role="button" data-nombre="07">JUL</a> 
            <a class="btn btn-default meses" role="button" data-nombre="08">AGO</a>
            <a class="btn btn-default meses" role="button" data-nombre="09">SEP</a>
            <a class="btn btn-default meses" role="button" data-nombre="10">OCT</a>
            <a class="btn btn-default meses" role="button" data-nombre="11">NOV</a>
            <a class="btn btn-default meses" role="button" data-nombre="12">DIC</a>
          </div>
        </div>        
      </div>
      <div class="row">
        <div class="col-xs-2">
          <!-- <h4>Jurisdicciones</h4>
          <div class="btn-group btn-block btn-group-vertical btn-group-sm">
            <button class="btn btn-default jur" type="button" data-jur="0" data-hospital="203">Hospital de la Mujer</button> 
            <button class="btn btn-default jur" type="button" data-jur="01" data-hospital="259">Zacatecas</button> 
            <button class="btn btn-default jur" type="button" data-jur="02" data-hospital="">Ojocaliente</button> 
            <button class="btn btn-default jur" type="button" data-jur="03" data-hospital="">Fresnillo</button> 
            <button class="btn btn-default jur" type="button" data-jur="04" data-hospital="">Rio Grande</button> 
            <button class="btn btn-default jur" type="button" data-jur="05" data-hospital="">Jalpa</button> 
            <button class="btn btn-default jur" type="button" data-jur="06" data-hospital="">Tlaltenango</button> 
            <button class="btn btn-default jur" type="button" data-jur="07" data-hospital="">Concepcion del Oro</button> 
          </div> -->
          <h3>Indicadores</h3>
          <div class="btn-group btn-block btn-group-vertical btn-group-sm">
            <button class="btn btn-default ind" type="button" data-nombre="nac_xCesarea">Nac x Cesarea</button> 
            <button class="btn btn-default ind" type="button" data-nombre="consPren">Cons Prenatales</button> 
            <button class="btn btn-default ind" type="button" data-nombre="usuAct">Usuarias Activas</button>
            <button class="btn btn-default ind" type="button" data-nombre="morbTbPulmonar">Morbilidad T.B. Pulmonar</button>
            <button class="btn btn-default ind" type="button" data-nombre="nuevosTbTaesTerm">Nvos T.B. TAES Term</button>
            <button class="btn btn-default ind" type="button" data-nombre="promConsMed">Consulta X Medico</button>
            <button class="btn btn-default ind" type="button" data-nombre="porcOcuHosp">Ocupacion Hospitalaria</button>
            <button class="btn btn-default ind" type="button" data-nombre="promDiasEst">Promedio Dias Estancia</button>
            <button class="btn btn-default ind" type="button" data-nombre="interQuir">Prom. Int. Quir. x Quirofano</button>
          </div>
        </div>
        <div id="resultado" class="col-xs-10"><br>
          <ul>
            <li>Selecciona un mes</li>
            <li>Selecciona un indicador</li>
          </ul>
        </div>
      </div>
      <br />
      <div class="row">
        <div class="col-xs-12">
          <div class="alert alert-dismissable alert-info">         
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>Información</h4>Selecciona un mes
          </div>
        </div>
      </div>
    </div>

    <?php include "/inc/footer_common.php" ?>
    
    <script src="../../js/ind_res.js"></script>
  </body>
</html>