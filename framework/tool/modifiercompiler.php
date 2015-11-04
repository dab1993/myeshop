<?php
	function smarty_modifiercompiler_path_url($params, $compiler)
	{
		echo $params[0];
		return Framework::pathToUrl($params[0]);
	}
