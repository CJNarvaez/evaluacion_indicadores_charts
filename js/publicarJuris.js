$(document).ready(function(){
    $("#espera").toggle();    
});

function publicarJuris(mes,anio)
{
    // confirm dialog
    alertify.confirm("¿Estas seguro de que quieres publicar este mes? (El proceso tardara de 1-5 min)", function (e) {
        if (e) {
            empezar();
            // user clicked "ok"
            $("#resultado").load('../evaluacion/publicarJuris/'+mes+'/'+anio);
        } else {
            // user clicked "cancel"
        }
    });
}
function publicarHc(mes,anio)
{
    // confirm dialog
    alertify.confirm("¿Estas seguro de que quieres publicar este mes? (El proceso tardara de 1-5 min)", function (e) {
        if (e) {
            empezar();
            // user clicked "ok"
            $("#resultado").load('../evaluacion/publicarHc/'+mes+'/'+anio);
        } else {
            // user clicked "cancel"
        }
    });
}
function publicarJurisHc(mes,anio)
{
    // confirm dialog
    alertify.confirm("¿Estas seguro de que quieres publicar este mes? (El proceso tardara de 1-5 min)", function (e) {
        if (e) {
            empezar();
            // user clicked "ok"
            $("#resultado").load('../evaluacion/publicarJurisHc/'+mes+'/'+anio);
        } else {
            // user clicked "cancel"
        }
    });
}
function publicar2Nivel(mes,anio)
{    
    // confirm dialog
    alertify.confirm("¿Estas seguro de que quieres publicar este mes? (El proceso tardara de 1-5 min)", function (e) {
        if (e) {
            empezar();
            // user clicked "ok"
            $("#resultado").load('../evaluacion/publicar2Nivel/'+mes+'/'+anio);
        } else {
            // user clicked "cancel"
        }
    });
}
function empezar()
{
    $("#btn_publicar").toggle();
    $("#espera").toggle();
}