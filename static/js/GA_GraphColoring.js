//$(document).ready(function(){
	
	/* Declarar Variables y Objetos
	-------------------------------------------------- */
	var nodes;
	var population;
	var time;
	var replacement;
	var connections;
	var solution;
	var fitness;
	
	// Correccion de datos de entrenamiento
	$("#calcular").live('click',function (event){
		event.preventDefault();
		
		error = false;
		
		nodes = $("#nodes").val();
		population = $("#population").val();
		time = $("#time").val();
		replacement = $("#replacement").val();
		
		if(nodes == "" || population == "" || time == "" || replacement == ""){
			error = true;
		}
		
		connections = $('textarea#connections').val();
		connections = connections.split("\n");
		
		$.each(connections, function(index, value) {
			connections[index] = value.split(" ");
			if(connections[index].length != 2){
				alert("Error de input en connections");
				error = true;
			}
		});
		console.log(connections);

		console.log("nodes: " + nodes + " population: "+population + " time: " + time + " replacement: " + replacement + " connections: " + connections);
		
		if(!error){
			execGA();
		}else{
			alert("Error en input");
		}

	});

	function execGA(){
		// Population rows, Node columns
		solution = new Array(population);
	
		fitness = new Array(population);
	
		//Generate radom solutions with nodes colors
		for(var i=0; i< population; i++){
			solution[i] = random_colors(nodes);
		};
	
		// Check number of death nodes
		var deaths = parseInt(Math.ceil(replacement*population)); 
	    var aux = parseInt(Math.floor((deaths/2.0)));

		// Check number of parents necessary
	    var number_parents = (aux%2==0) ? parseInt(aux) : parseInt(aux+1);

		// Generation count
		var generation = 0;
	
		// Ending condition
		var start = Date.now();
		var end = start;
	
		while ((Date.now() - start)/1000 < time) {
			//console.log("Generation " + (generation++));
			
			for (i = 0; i < population; i++) {
				//console.log("solution["+i+"]:"+solution[i]);
				fitness[i] = fitness_function(solution[i]);
				//console.log(i+" = " + solution[i] + " -> " + fitness[i]);
			};
		
			parents = min_n(fitness, number_parents);
			casualities = max_n(fitness, deaths);
		
			for (i = 0; i < deaths; i++){
				a = solution[parents[(i % number_parents)]];
				b = solution[parents[((i + 1) % number_parents)]];
				rand = parseInt(Math.random() * solution[0].length);
				solution[casualities[i]] = reproduce(a, b, rand);
			
				// Mutation
				if(parseInt(Math.random() * 2) == 1){
					mutate(solution[casualities[i]]);
				}
			};
		
		}
	
		for (i = 0; i < population; i++) {
			fitness[i] = fitness_function(solution[i]);
		}
		
		var best_solution = solution[min_n(fitness, 1)[0]];
		
		console.log("Best Solution: " +  best_solution);
		console.log("Number of Colors: " + count_colors(solution[min_n(fitness, 1)[0]]));
		
		if(check_valid_solution(best_solution)){
		}else{
			//alert("Solucion más óptima no valida, buscando en las otras opciones.");
			found_other_sol = false;
			for(i = 0; i < solution.length; i++){
				if(check_valid_solution(solution[i])){
					best_solution = solution[i];
					encuentra_otra = true;
				}
			}
			
			if(found_other_sol){
				alert("Opcion válida encontrada.");
			}else{
				// Fix best solution so it works
				best_solution = fix_best_solution(best_solution);
			}
		}
		// Pinta Arbol
		paint_graph(best_solution, count_colors(best_solution));
	}

	// Return array sorted from min to max
	function min_n(arr, n){
		var result = new Array(n);
	
		for(var a = 0; a < n; a++){
			min = arr[0];
			index = 0;
		
			for(i = 0; i < arr.length; i++){
				if(arr[i] < min){
					max = arr[i];
					index = i;
				}
			}
		
			result[a] = index;
			arr[index] = Number.MAX_VALUE;
		}
	
		return result;
	}

	// Return array sorted from max to min
	function max_n(arr, n){
		var result = new Array(n);
	
		for(var a = 0; a < n; a++){
			max = -1;
			index = -1;
		
			for(i = 0; i < arr.length; i++){
				if(arr[i] > max){
					max = arr[i];
					index = i;
				}
			}
		
			result[a] = index;
			arr[index] = -1;
		}
	
		return result;
	}

	function random_colors(n){
		rand_a = parseInt(Math.random() * (n*100));
		result = new Array(n);
	
		for (i = 0; i < n; i++) {
			result[i] = i;
		};
	
		for(i=0; i < rand_a; i++){
			// Swap result
			swap(result);
		};
	
		return result; 
	}

	function swap(arr){
		rand_a = parseInt(Math.random() * arr.length);
		rand_b = parseInt(Math.random() * arr.length);
	
		aux = arr[rand_a];
		arr[rand_a] = arr[rand_b];
		arr[rand_b] = aux;
	}

	function mutate(arr){
		rand_a = parseInt(Math.random() * arr.length);
		rand_b = parseInt(Math.random() * arr.length);
	
		arr[rand_a] = arr[rand_b];
	}

	function fitness_function(solution){
		var result = 0;
		
		for (i = 0; i < connections.length; i++) {
			if(solution[connections[i][0]] == solution[connections[i][1]]){
				result += 15;
			}
		};
    
		// Minimize number of colors
		result += count_colors(solution) * 5;
	
		return result;
	}
	
	function check_valid_solution(solution){
		valid = true;
		//console.log("START---");
		for (i = 0; i < connections.length; i++) {
			if(solution[connections[i][0]] == solution[connections[i][1]]){
				//console.log("Colision en "+connections[i][0]+" y "+connections[i][1]);
				valid = false; 
			}
		};
		return valid;
	}
	
	function fix_best_solution(best_solution){
		var conflicts;
		for (i = 0; i < connections.length; i++) {
			if(best_solution[connections[i][0]] == best_solution[connections[i][1]]){
				console.log("Conflicto en "+connections[i][0]+" y "+connections[i][1] + ". Cambio color.");
				// Si tiene conflicto agregas nuevo color
				var new_color = Math.floor(Math.random() * 16)
				while(jQuery.inArray(new_color, best_solution) != -1){
					new_color = Math.floor(Math.random() * 16)
				}
				console.log("Cambio localidad " + connections[i][0] + " a nuevo color: " + new_color);
				best_solution[connections[i][0]] = new_color;
			}
		};
		if(check_valid_solution(best_solution)){
			console.log("Ahora si es valida...");
			return best_solution;
		}else{
			console.log("No se pudo corregir :(");
			return best_solution;
		}
		
	}
	
	function count_colors(solution){
		color_number = 0;
		colors = new Array(solution.length);
	
		for(i = 0; i < solution.length; i++){
			colors[solution[i]] = true; 
		};
	
		for(i = 0; i < colors.length; i++){
			if(colors[i]){
				color_number++;
			}
		};
	
		return color_number;
	}

	function reproduce(a, b, splitter){
		result = new Array(a.length);
	
		for(i=0; i<a.length; i++){
			if (i<splitter){
				result[i] = a[i];
			}else{
				result[i] = b[i];
			}
		};
	
		return result;
	}

//});
