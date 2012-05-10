<?php

	/**
	 * The Hello World class!
	 *
	 * @author Michiel Rook
	 * @version $Id: HelloWorld.php 426 2008-10-28 19:29:49Z mrook $
	 * @package hello.world
	 */
	class HelloWorld
	{
		public function foo($silent = true)
		{
			if ($silent) {
				return;
			}
			return 'foo';
		}

		function sayHello()
		{
			return "Hello World!";
		}
	};

?>