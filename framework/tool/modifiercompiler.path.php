<?php

	 function smarty_modifiercompiler_path($params,$compiler)
	{
		$cont=preg_replace("/'/",'',$params[0]);
		return $cont.')nihao';
	}
