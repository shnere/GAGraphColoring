<div class="hero-unit" style="padding-bottom: 30px">
	<h1>Genetic Algorithms, Graph Coloring</h1>
	
	<div class="mts">&nbsp;</div>
	
	<form id="" method="POST" action="" class="mt">
		<div class="row">
			<div class="span4">
				<span id=""><b>1. </b>Seleccionar número de nodos: </span>
			</div>
			<input type="number" class="span3" required="true" placeholder="5" min="1" value="5" id="nodes">
		</div>
		<div class="row">
			<div class="span4">
				<span id=""><b>2. </b>Seleccionar el tamaño de la población: </span>
			</div>
			<input type="number" class="span3" required="true" placeholder="20" min="1" value="20" id="population">
		</div>
		<div class="row">
			<div class="span4">
				<span id=""><b>3. </b>Seleccionar tiempo de corrida del algoritmo: </span>
			</div>
			<input type="number" class="span3" required="true" step="any" placeholder="2 segundos" min="0" value="3" id="time">
		</div>
		<div class="row">
			<div class="span4">
				<span id=""><b>4. </b>Seleccionar porcentaje de reemplazo por generación: </span>
			</div>
			<input type="number" class="span3" required="true" step="any" placeholder=".25" min="0" value=".25" id="replacement">
		</div>
		<div class="row">
			<div class="span4">
				<span id=""><b>5. </b>Indicar conexiones: </span>
			</div>
			<textarea class="span3" name="" id="connections" rows="4" tabindex="">0 1
0 2
0 3
1 3
1 4
2 3
3 4</textarea>
		</div>
		<input type="button" id="calcular" class="btn btn-primary btn-large" style="margin-top: 2px;" value="Calcular">
	</form>
	
</div>

<section>
	<h2>Resultados</h2>
	<div class="page-header"></div>
	<div class="row">
		<div class="span9">
			<canvas id="viewport" width="800" height="600"></canvas>
		</div>
	</div>
</section>