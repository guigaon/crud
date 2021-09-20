<?php
    require "autoload.php";
	
/**@author Guilherme Kirsch <guilherme-bkirsch@educar.rs.gov.br>
 * @version 0.1 
 * @copyright CIMOL © 2021. 
 * @access public 
 * @link http://www.linhadecodigo.com.br/artigo/1089/phpdoc-documentando-bem-seu-codigo.aspx#ixzz76aRead
 */

	
	
	/*
	
	* identifica se a url está vazia, se sim, envia para professor.php, se nao achar nenhum arquivo existente, vai para pagina de erro
	* @access private 
	*/
	$url = (isset($_GET['url'])) ? $_GET['url']:'professores.php';
			$url = array_filter(explode('/',$url));
			
			$file = $url[0].'.php';
			
			if(is_file($file)){
				include $file;
			}else{
				include '404.php';
			}			
			
			
			
			
