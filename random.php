<?php 
/* RANDOM */
	function make_seed()
	{
  		list($usec, $sec) = explode(' ', microtime());
  		return (float) $sec + ((float) $usec * 100000);
	}
	function get_rand($min, $max)
	{
		srand(make_seed());
		return rand($min, $max);
	}
	function get_randString($length)
	{
		$str = '';
		$c = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		$max = count($c) - 1;
		$last = 0;
		for($i=0; $i<$length; $i++)
		{
			$new_rand = get_rand(0, $max);
			$used_rand = ($new_rand + $last) % $max;
			$str = $str.$c[$used_rand];
			$last = $used_rand;
		}
		return $str;
	}
	function get_randNumString($length)
	{
		$str = '';
		$c = array_merge(range('0','9'));
		$max = count($c) - 1;
		$last = 0;
		for($i=0; $i<$length; $i++)
		{
			$new_rand = get_rand(0, $max);
			$used_rand = ($new_rand + $last) % $max;
			$str = $str.$c[$used_rand];
			$last = ($used_rand + 7) % $max;
		}
		return $str;
	}
?>