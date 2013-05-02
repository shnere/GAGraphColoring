
var connections;

function main(nodes, aristas, population, time, replacement, connections){
	
	// Population rows, Node columns
	var solutions = new Array(population);
	
	var fitness = new Array(population);
	
	//Generate radom solutions with nodes colors
    for(i=0; i< solutions.length; i++){
        solutions[i] = random_colors(nodes);
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
		console.log("Generation " + (generation++));
		for (i = 0; i < population; i++) {
			fitness[i] = fitness_function(solutions[i]);
			console.log(i+" = " + solutions[i] + " -> " + fitness[i]);
		};
		
		parents = min_n(fitness, number_parents);
		casualities = max_n(fitness, deaths);
		
		for (i = 0; i < deaths; i++){
			a = solutions[parents[(i % number_parents)]];
			b = solutions[parents[((i + 1) % number_parents)]];
			rand = parseInt(Math.random() * solutions[0].length);
			solutions[casualities[i]] = reproduce(a, b, rand);
			
			// Mutation
			if(parseInt(Math.random() * 2) == 1){
				mutate(solutions[casualities[i]]);
			}
		};
		
	}
	
	for (int i = 0; i < population; i++) {
		fitness[i] = fitness_function(solutions[i]);
	}
	console.log("Best Solution: " +  solutions[min_n(fitness, 1)[0]]);
	console.log("Number of Colors: " + count_colors(solutions[min_n(fitness, 1)[0]]));
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
		arr[index] = Integer.MAX_VALUE;
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
	
	for (i = 0; i < result.length; i++) {
		result[i] = i;
	};
	
	for(int i=0; i<rand_a; i++){
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
	result += countColors(solution) * 5; 
	
	return result;
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
	
	for(int i=0; i<a.length; i++){
		if (i<splitter){
			result[i] = a[i];
		}else{
			result[i] = b[i];
		}
	};
	
	return result;
}

