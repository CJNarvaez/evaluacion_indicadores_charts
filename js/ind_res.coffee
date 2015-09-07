btnMeses = $(".meses")
btnIndicadores = $(".ind")
#btnJuris = $(".jur")
ind = 0
mes = 0
#jur = 0

$ ->
	btnMeses.click btnMesPres
	btnIndicadores.click btnIndPres
	#btnJuris.click btnJurPres

btnMesPres = () ->
	btnMeses.removeClass "active"
	$(@).addClass "active"
	mes = $(@).data "nombre"
	# if jur isnt 0 and ind isnt 0 <-- condicion anterior para cuando tomaba en cuenta la jurisdiccion
	if ind isnt 0
		creaLoad()
		$("#resultado").load "../ind_res/"+ind+"/"+mes+"/2015"

btnIndPres = () ->
	btnIndicadores.removeClass "active"
	$(@).addClass "active"
	ind = $(@).data "nombre"
	# if jur isnt 0 and mes isnt 0 <-- condicion anterior para cuando tomaba en cuenta la jurisdiccion
	if mes isnt 0
		creaLoad()
		$("#resultado").load "../ind_res/"+ind+"/"+mes+"/2015"

creaLoad = () ->
	$(".resultado").remove()
	$("#resultado").append '<div class="text-center alert-info"><img src="../../img/loading.gif" class="" alt="Cargando"><h1>Cargando...</h1></div>'
	
###
btnJurPres = () ->
	btnJuris.removeClass "active"
	$(@).addClass "active"
	jur = $(@).data "nombre"
	if ind isnt 0 and mes isnt 0
		$("#resultado").load "../ind_res/"+ind+"/"+jur+"/"+mes+"/2014"
###
