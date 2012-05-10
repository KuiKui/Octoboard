<?php

    require_once "PHPUnit/Framework/TestCase.php";
    require_once "HelloWorld.php";

    /**
    * Test class for HelloWorld
    *
    * @author Michiel Rook
    * @version $Id: HelloWorldTest.php 425 2008-10-28 19:27:01Z mrook $
    * @package hello.world
    */
    class HelloWorldTest extends PHPUnit_Framework_TestCase
    {
        public function testSayHello()
        {
            $hello = new HelloWorld();
            $this->assertEquals("Hello World!", $hello->sayHello());
        }
    }

?>
