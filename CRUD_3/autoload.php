<?php

spl_autoload_register(
		function($classe){
			require "classes/$classe.php";
		}
		
	);

?>