<?php
class AdictosTutorial {
 
    public function greet() {
        return 'Hola Adictos Al Trabajo !!!';
    }
}

class AdictosTutorialTest extends PHPUnit_Framework_TestCase {
 
    public function testReturnGreeting() {
        $adictos = new AdictosTutorial();
        $this->assertEquals('Hola Adictos Al Trabajo !!!', $adictos->greet());
    }
}
