<?php 

/* RANDOM */
	function make_seed()
	{
  		list($usec, $sec) = explode(' ', microtime());
  		return (float) $sec + ((float) $usec * 100000);
	}

	function get_rand($min, $max)
	{
		srand($this->make_seed());
		return rand($min, $max);
	}
    
	function get_randString($length)
	{
		$str = '';
		$c = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		$max = count($c) - 1;
		for($i=0; $i<$length; $i++)
		{
			$str = $str.$c[$this->get_rand(0, $max)];
		}
		return $str;
	}

?>