var azul, amarillo, rojo, verde, blanco;

$(document).ready(function(){
    
    azul =  $('.azul');
    amarillo = $('.amarillo');
    rojo = $('.rojo');
    verde = $('.verde');
    blanco = $('.blanco');
    
    azul.append('<img src="../../img/azul.jpg" alt="" />');
    amarillo.append('<img src="../../img/amarillo.jpg" alt="" />');
    rojo.append('<img src="../../img/rojo.jpg" alt="" />');
    verde.append('<img src="../../img/verde.jpg" alt="" />');
    blanco.append('<img src="../../img/blanco.jpg" alt="" />');
});

function muestra(semaforo)
{    
    azul.parent().parent().hide();
    amarillo.parent().parent().hide();
    rojo.parent().parent().hide()
    verde.parent().parent().hide();
    blanco.parent().parent().hide();
    
    switch (semaforo) {
        case 'azul': azul.parent().parent().show();
                        break;
        case 'amarillo': amarillo.parent().parent().show();
                        break;
        case 'rojo': rojo.parent().parent().show();
                        break;
        case 'verde': verde.parent().parent().show();
                        break;
        case 'blanco': blanco.parent().parent().show();
                        break;
        case 'todos': azul.parent().parent().show();
                        amarillo.parent().parent().show();
                        rojo.parent().parent().show();
                        verde.parent().parent().show();
                        blanco.parent().parent().show();
                        break;
    }
}