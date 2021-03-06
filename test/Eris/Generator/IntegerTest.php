<?php
namespace Eris\Generator;

class IntegerTest extends \PHPUnit_Framework_TestCase
{
    public function testPicksRandomlyAnInteger()
    {
        $generator = new Integer();
        for ($i = 0; $i < 100; $i++) {
            $this->assertTrue($generator->contains($generator()));
        }
    }

    public function testShrinksLinearlyTowardsZero()
    {
        $generator = new Integer(-10, 10);
        $value = $generator();
        for ($i = 0; $i < 20; $i++) {
            $newValue = $generator->shrink($value);
            $this->assertTrue(in_array(abs($value - $newValue), [0, 1]));
            $value = $newValue;
        }
        $this->assertSame(0, $value);
    }

    public function testShrinkStopsAtTheLowerLimitWhenItIsGreaterThanZero()
    {
        $generator = new Integer(10, 20);
        $value = $generator();
        for ($i = 0; $i < 11; $i++) {
            $value = $generator->shrink($value);
        }
        $this->assertSame(10, $value);
    }

    public function testShrinkStopsAtTheUpperLimitWhenItIsLowerThanZero()
    {
        $generator = new Integer(-20, -10);
        $value = $generator();
        for ($i = 0; $i < 11; $i++) {
            $value = $generator->shrink($value);
        }
        $this->assertSame(-10, $value);
    }

    public function testUniformity()
    {
        $generator = new Integer(-10, 10000);
        $values = [];
        for ($i = 0; $i < 50; $i++) {
            $values[] = $generator();
        }
        $this->assertGreaterThan(
            40,
            count(array_filter($values, function($n) { return $n > 0; })),
            "The positive numbers should be a vast majority given the interval [-10, 10000]"
        );
    }

    public function testCannotShrinkStopsToZero()
    {
        $generator = new Integer($lowerLimit = 0, $upperLimit = 0);
        $lastValue = $generator();
        $this->assertSame(0, $generator->shrink($lastValue));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionWhenDomainBoundariesAreNotIntegers()
    {
        $generator = new Integer("zero", "twenty");
    }

    public function testCanGenerateSingleInteger()
    {
        $generator = new Integer(42, 42);
        $this->assertSame(42, $generator());
        $this->assertSame(42, $generator->shrink($generator()));
    }

    /**
     * @expectedException DomainException
     */
    public function testExceptionWhenTryingToShrinkValuesOutsideOfTheDomain()
    {
        $generator = new Integer(100, 200);
        $generator->shrink(300);
    }

    public function testTheOrderOfBoundariesDoesNotMatter()
    {
        $this->assertEquals(
            new Integer(100, -100),
            new Integer(-100, 100)
        );
    }
}
