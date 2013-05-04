<?php if (! defined('BASEPATH')) exit('No direct script access');

class Main extends CI_Controller {

	//php 5 constructor
	function __construct() {
		parent::__construct();

	}
	
	function index() {
		global $data;
		// Loading of dynamic libraries
		//$data['dinamicLibrary']['libraryName'] = true;
		
		/* Configuration Information */
		$data['SYS_metaTitle'] 			= 'Genetic Algorithms, Graph Coloring';
		$data['SYS_metaKeyWords'] 		= 'online graph coloring, graph coloring, ga, genetic algorithms';
		$data['SYS_metaDescription'] 	= 'A solution of the graph coloring problem using genetic algorithms.';
		$data['module'] 				= 'ga_graphcoloring_view';
		$this->load->view('/template/main_view', $data);
	}

	/****************************************************
	 *													*
	 *					   Views	 					*
	 *													*
	 ***************************************************/


	/****************************************************
	 *													*
	 *					   Methods	 					*
	 *													*
	 ***************************************************/

	/****************************************************
	 *													*
	 *					   	Ajax	 					*
	 *													*
	 ***************************************************/

}
/* End of file main.php */ 
/* Location: /application/controllers/main.php */