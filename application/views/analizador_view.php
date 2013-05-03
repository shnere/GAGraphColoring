<script>
$(document).ready(function(){
	
	/* Declarar Variables y Objetos
	-------------------------------------------------- */
	var termino = "";
	var clasificador_entrenado = false;
	// Metodo (algoritmo) default de entrenamiento
	var metodo = 0;
	// Conjunto de datos de entrenamiento
	var conjunto = 0;
	// Creas el proxy
	var proxee = new Proxy();
	// Creas el realsubject del proxy
	var meromeroSubject = new RealSubject();
	
	
	// Crear objeto texto
	function Texto(id, fecha, usuario, usuario_id, thumb, texto, metodo, conjunto){
		this.idee = id;
		this.fecha = fecha;
		this.usuario = usuario;
		this.usurio_id = usuario_id;
		this.thumb = thumb;
		this.texto = texto;
		// Metodo (algoritmo) a utilizar
		this.metodo = metodo;
		// Conjunto de datos utilizado
		this.conjunto = conjunto;
		// Resultado de analisis
		this.analisis = "default";
		this.probabilidad_positivo = 0;
		this.probabilidad_negativo = 0;
	}
	
	function Proxy(){
		this.textos = [];
		this.elementos = 0;
		this.analizar = function(txt,metodo){
			// Solamente llamas al realsubject
			meromeroSubject.analizar(txt,metodo);
			
		};
		this.esNuevoTexto = function(txt){
			if(this.textos.length < 1){
				return true;
			}
			$.each(this.textos, function(i,val) {
				if(val.texto.indexOf(txt) !== -1 || val.texto === txt){
					console.log("Ya existe, no lo agrego "+txt);
					return false;
				}
			});
			
			return true;
		};
	}
	
	function RealSubject(){
		this.analizar = function(txt,metodo){
			//console.log("Proxy me pide analizar " + txt.texto);
			texto_analizar = ((metodo == 1) ? cleanText(txt.texto) : eliminaQuery(txt.texto));
			$.ajax({
				url: "http://api.arodriguez.mx/api/analizar/?texto="+texto_analizar+"&metodo="+metodo,
				dataType: 'jsonp',
				/*async: false,*/
				success: function(data) {
					txt.analisis = data.resultado;
					txt.probabilidad_negativo = data.probabilidad_negativo;
					txt.probabilidad_positivo = data.probabilidad_positivo;

					// Agrega texto y resultado
					$('#analisis tr:last').after(prepareText(txt));

					// Semi supervised learning cuando es mayor a 98%
					if(txt.probabilidad_positivo > .97 || txt.probabilidad_negativo > .97){
						// Mientras pruebo comento el SSL
						console.log("Agregar Texto: "+txt.texto);
						//agrega_texto(txt.texto,txt.analisis);
					}

					// Llama metodo update
					$(document).trigger('TextUpdate', [txt]);
					return;
				}
			});
		}
	}
	
	/* Funciones Utilidad
	-------------------------------------------------- */
	function entrenar(){
		$.ajax({
			url: "http://api.arodriguez.mx/api/entrenar/?metodo="+metodo+"&conjunto="+conjunto,
			dataType: 'jsonp',
			/*async: false,*/
			success: function(data) {
				if(data.resultado == "true"){
					console.log("entrenado");
				}else{
					console.log("error");
				}
				return;
			}
		});
	}
	
	// Primer Paso, checar que este entrenado el clasificador
	$.ajax({
		url: "http://api.arodriguez.mx/api/entrenar/?metodo="+metodo+"&conjunto="+conjunto,
		dataType: 'jsonp',
		/*async: false,*/
		success: function(data) {
			if(data.error == "false"){
				console.log("entrenado");
			}else{
				// Tienes que entrenar
				//entrenar();
			}
		}
	});
	
	// 1. Obtiene query de twitter
	function getQuery(search_term,cuantos,tipo){
		var items = [];
			
		$.ajax({
			url: "http://search.twitter.com/search.json?q=" + search_term + "&result_type=recent&lang=es&callback=?&rpp="+cuantos+"&page=" + Math.round(proxee.elementos/10)+1,
			dataType: 'json',
			async: false,
			success: function(data) {
				manipulaResultadosBusqueda(data);
				return;
				$.each(data.results, function() {
					//console.log(tipo+": "+this.text);
					proxee.elementos++;
					//pos = Math.floor(Math.random()*elementos-1);
					$('#analisis tr').eq(proxee.elementos).after(prepareText(/*tipo+": "*/cleanText(this.text)));
				});
			}
		});
	}
	
	// 2. Manipula resultados
	function manipulaResultadosBusqueda(data){
		console.log(data);
		
			// Registra observadores
		
		// En este caso registro un observador que checara el arreglo de Textos
		// Cuando se modifique entonces checo si ya termino para hacer mas busquedas
		$(document).on("TextUpdate", proxee.textos, function(event){
		cambioTextoHandler(event.data)});
		
			// TODO Declarar observador grafica, cuando se llama su metodo update actualiza grafica
		
		// Recorrer resultados
		$.each(data.results, function() {
			// Toma accion solo si es un nuevo texto
			if(proxee.esNuevoTexto(this.text) === true){
				// Crear objeto
				proxee.textos[proxee.elementos] = new Texto(proxee.elementos, this.created_at,this.from_user, this.from_user_id, this.profile_image_url, this.text, metodo, conjunto);

				// Realizar analisis
				proxee.analizar(proxee.textos[proxee.elementos],metodo);
				//analizar(textos[proxee.elementos],0);
				//pos = Math.floor(Math.random()*proxee.elementos-1);
				
				// Incrementa elementos
				proxee.elementos++;
			}

		});
		console.log(proxee.textos);
	}
	
	// Funcion del observer
	function cambioTextoHandler(txt){
		$("#loading").fadeOut();
		termina = true
		for(i=0;i<txt.length;i++){
			if(txt[i].analisis == "default"){
				termina = false;
			}
		}
		
		if(termina){
			console.log("Termina!!");
			// Pinta grafica
			drawChart();
			// Busca mas
			if(proxee.elementos < 50){
				setTimeout(getQuery(termino,5,""), 1000)
				//getQuery(termino,10,"");
			}
		}
	}
	
	
	/* Metodos Utilerias
	-------------------------------------------------- */
	
	// Recibe un objeto de la clase txt y dependiendo de su resultado lo pone en la grafica
	function prepareText(txt){

		ret_text = '<tr class="elemento'+txt.idee+'">';
		probabilidad_alta = false;
		if(txt.probabilidad_positivo > .90 || txt.probabilidad_negativo > .90){
			probabilidad_alta = true;
		}
		// Suavizar datos entre .45 y .55 se considera neutral
		if(txt.probabilidad_positivo > .40 && txt.probabilidad_positivo < .60){
			txt.analisis = "neutral";
		}
		ret_text += '<td class="">';
		
		if (txt.analisis == "positivo") {
			ret_text += '<span class="label label-success"> <i class="icon-thumbs-up icon-white"></i> Positivo';
			positivos++;
		}
		if (txt.analisis == "negativo") {
			ret_text += '<span class="label label-important"> <i class="icon-thumbs-down icon-white"></i> Negativo';
			negativos++;
		}
		if (txt.analisis == "neutral") {
			ret_text += '<span class="label">Neutral';
			neutrales++;
		}
		if (txt.analisis == "desconocido") {
			ret_text += '<span class="label">Desconocido';
		}
		
		if (probabilidad_alta){
			ret_text += '</span> Probabilidad Alta';
		}else{
			ret_text += '</span>';
		}
		
		ret_text += '<div class="btn-group"><button class="btn btn-warning btn-small dropdown-toggle" data-toggle="dropdown"><i class="icon-edit icon-white"></i> Corregir <span class="caret"></span></button><ul class="dropdown-menu"><li><a class="corregir positivo" href="#" txt_id="'+txt.idee+'"><i class="icon-thumbs-up"></i> Positivo</a></li><li><a class="corregir negativo" txt_id="'+txt.idee+'"><i class="icon-thumbs-down"></i> Negativo</a></li></ul></div>';
		ret_text += '</td>';
		ret_text +='<td class="content">';
		
		// Agrega Imagen
		ret_text +=  '<img src="'+txt.thumb+'" alt="'+ txt.usuario + '"></img>';
		ret_text +=  ' <a href="www.twitter.com/' + txt.usuario + '" title="' + txt.usuario + '"> @'+txt.usuario+'</a>';
		ret_text +=  '<p style="margin-top:15px;">'+txt.texto+"</p>";
		ret_text +=  '</td></tr>';
		
		return ret_text;
	}
	
	// Elimina de un texto dado el query o termino de busqueda con el objetivo de que sean las otras palabras
	// las que influyan en el resultado y no el termino de busqueda
	function eliminaQuery(texto){
		return texto.replace(termino,'');
	}
	
	function agrega_texto(texto,valor){
		$.ajax({
			url: "http://api.arodriguez.mx/api/agregar/?texto="+texto+"&valor="+valor+"&conjunto="+conjunto+"&key=7sd235shja5h26",
			dataType: 'jsonp',
			success: function(data) {
				if(data.agregado == "true"){
					console.log("Agregado Texto: "+texto);
				}else{
					console.log("error");
				}
				return;
			}
		});
	}
	
	function cleanText(text){
		// Borra Arrobas
		//text = text.replace( /(^|\s|\")@(\S+)/g, '');
		// Borra Hashtags
		//text = text.replace( /(^|\s)#(\S+)/g, '');
		// Borra RT
		//text = text.replace( /\R\T/g, '');
		// Borra URL
		text = text.replace( /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/g, '');
		
		return text;
	}
	

	$("#nuevo_termino").click(function(event){
		event.preventDefault();
		window.location.reload();
	})

	$('#enviar_termino').submit(function(event){
		event.preventDefault();
		termino = $('#termino_busqueda').val();
		
		if(termino != ""){
			$("#loading").fadeIn();
			console.log("Busco: "+ termino);

			// Obtienes datos del query, callback manipulaResultadosBusqueda
			getQuery(termino,10,"");

			console.log("elementos: "+proxee.elementos);
		}
	});
	
	// Configuracion Conjunto entrenamiento
	$("#conjunto_entrenamiento").change(function(){
		conjunto = $("#conjunto_entrenamiento option:selected").val();
		console.log("Cambio conjunto a " + conjunto);
	});
	
	// Correccion de datos de entrenamiento
	$(".corregir").live('click',function (event){
		event.preventDefault();

		// Obtengo texto
		t = $(this).parent().parent().closest('td').next().find("p").html();
		if($(this).hasClass("positivo")){
			//console.log("Agrega texto positivo "+t);
			agrega_texto(t,"positivo");
		}else{
			//console.log("Agrega texto negativo "+t);
			agrega_texto(t,"negativo");
		}
		
	});
	
	// Configuracion Algoritmo
	$("#metodo_algoritmo").change(function(){
		metodo = $("#metodo_algoritmo option:selected").val();
		console.log("Cambio metodo a " + metodo);
	});
	
	$('#enviardatos').click(function(event){
		event.preventDefault();
		arr = {};
		for(i=1;i<proxee.elementos;i++){
			arr[$('tr.elemento'+i+' td.content').html()] = $('input[name=optionsRadios'+i+']:checked').val();
		}
		
		arr = JSON.stringify(arr);
		console.log(arr);
		
		$.ajax({
			url: '<?php echo base_url(); ?>main/agregaResultados',
			type: "POST",
			data: {resultados: arr, nombreUsuario: $('#nombre').val(), ip: $('#ipcliente').val()},
			async: false,
			success: function(msg){
				alert("¡Gracias por tu ayuda! Si quieres seguir ayudando solo tienes que darle refresh a la página");
			},
			error: function(data){
				alert("Hubo un error :( JUAY!!!");
				return false;
			}
		});
		
	});
}); 
</script>


<div class="hero-unit" style="padding-bottom: 30px">
	<h1>Analizador de Sentimientos</h1>
	<h2>Búsqueda de sentimientos en twitter</h2>
	
	<div class="mts">&nbsp;</div>
	
	<form id="" method="POST" action="" class="mt">
		<div class="row">
			<div class="span4">
				<span id="filtrar-por"><b>1. </b>Seleccionar conjunto (corpus) de entrenamiento: </span>
			</div>
		<select id="conjunto_entrenamiento" name="conjunto_entrenamiento" class="span3" >
			<option value="0" selected="">General</option>
			<option value="1">Fútbol</option>
		</select>
		</div>
	</form>
	
	<form id="" method="POST" action="">
		<div class="row">
			<div class="span4">
				<span id="filtrar-por"><b>2. </b>Seleccionar algoritmo a utilizar: </span>
			</div>
		<select id="metodo_algoritmo" name="metodo_algoritmo" class="span3" >
			<option value="0" selected="">Clasificador Bayesiano Ingenuo</option>
			<option value="1">Analisis por smileys</option>
		</select>
		</div>
	</form>
	
	<div id="buscarTweeets">
		<form id="enviar_termino" method="POST" action="" class="mt">
		<div class="row">
			<div class="span5">
				<input type="text" class="span5" required="true" placeholder="Buscar en twitter..." style="font-size: 18px; height: 37px; background-color: #fff" id="termino_busqueda">
			</div>
			<div class="span4">
				<input type="submit" id="buscar_twitter" class="btn btn-primary btn-large" style="margin-top: 2px;" value="Buscar Tweets">
			</div>
		</div>
		</form>
	</div>
	
</div>


<section style="display:none;">
	<div class="page-header">
		<h1>Instrucciones</h1>
	</div>
	<div class="well">
		
		Este programa se encarga de realizar análisis de sentimiento de textos en twitter, tiene la capacidad de probarse con diferentes algoritmos y con diferentes conjuntos de datos de entrenamiento, lo cual influye en el resultado final.<br><br
		
		
		<b>Ejemplo:</b><br>
		<span class="label label-success"><i class="icon-thumbs-up icon-white"></i> Positivo:</span> Es un bonito día.<br>
		<span class="label">Neutral:</span> Estoy comiendo papas con sal y catsup.<br>
		<span class="label label-important"><i class="icon-thumbs-down icon-white"></i> Negativo:</span> Coca-Cola Zero es un pésimo refresco.
		
		<br><br><b>Nota:</b> Si el tuit no representa un sentimiento, es ambigüo o no estás seguro, clasifícalo como <span class="label">Neutral</span>
	</div>
</section>

<section>
	<h2>Resultados</h2>
	<div class="page-header"></div>
	<div class="row">
		<div class="span9">
			<table id="analisis" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Resultado</th>
						<th>Texto</th>
					</tr>
				</thead>
				<tbody>
					<tr style="display:none;">
						<td class="">
							<label class="checkbox inline">
								<input type="radio" name="optionsRadios" id="positivo" value="positivo">
								<span class="label label-success"><i class="icon-thumbs-up icon-white"></i> Positivo</span>
							</label>
							<label class="checkbox inline">
								<input type="radio" name="optionsRadios" id="neutral" value="neutral" checked>
								<span class="label">Neutral</span>
							</label>
							<label class="checkbox inline">
								<input type="radio" name="optionsRadios" id="negativo" value="negativo">
								<span class="label label-important"><i class="icon-thumbs-down icon-white"></i> Negativo</span>
							</label>
						</td>
						<td>Content</td>
					</tr>
				</tbody>
			</table>
			<input type="button" id="nuevo_termino" class="btn btn-primary btn-large" style="margin-top: 2px;" value="Nuevo Término">
		</div>
		<div class="span2">
			<div id="chart_div"></div>
		</div>
	</div>
</section>