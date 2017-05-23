<?php
class Navigation {

	var $navigation = array();
	
	function __construct(){
		$this->populate();
	}
	
	public function set_navigation( $nav = array() ){
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/'); //trimming the /
		$url = explode('/', $url); //explode the url
		$active = FALSE;
		
		if( $url[0] == $nav['link'] ) {
			$active = TRUE;
		}else{
			$active = FALSE;
		}
		
		$this->navigation[] = array(
			'link' => URL . $nav['link'],
			'value' => $nav['value'],
			'is_active' => $active,
		);
	}	
	
	function populate(){
		/*		$this->set_navigation( array(
			'link' => '',
			'value' => 'Home'
		) );

		$this->set_navigation( array(
			'link' => 'index/asset',
			'value' => 'Asset'
		) ); 		*/
	}

}