<?php

class Driver
{
	function __call($name, $params)
	{
		echo "Received command *{$name}* with paramert *" . array_pop($params) . "*<br>";
	}
}
