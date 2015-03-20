$(document).ready(function(){
    var capturaCuadro = $("#capturaCuadro"); 
    
    if($("#captura").css('display') == 'none'){
        capturaCuadro.removeClass("col-md-4");
        capturaCuadro.addClass("col-md-2");
    }
        
});