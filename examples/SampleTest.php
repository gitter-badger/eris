<?php

class SampleTest extends \PHPUnit_Framework_TestCase
{
    use Eris\TestTrait;

    public function testSamplingValues()
    {
        $generator = $this->genNat();
        var_dump($this->sample($generator));
    }    

    public function testSamplingShrinking()
    {
        $generator = $this->genNat();
        var_dump($this->sampleShrink($generator));
    }    
}