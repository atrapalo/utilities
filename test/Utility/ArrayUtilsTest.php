<?php

namespace JimmyOak\Test\Utility;

use JimmyOak\Utility\ArrayUtils;

class ArrayUtilsTest extends UtilsBaseTest
{
    /** @var ArrayUtils */
    protected $utils;

    private $expectedParsedXmlString;
    private $arrayToParseAsXml = array(
        'details' => array(
            'media' => array(
                'image' => array(
                    'anImage.png',
                    'anotherImage.png',
                ),
                'video' => 'aVideo.mp4',
                'audio' => array(),
            )
        )
    );
    private $expectedParsedXml;

    protected function setUp()
    {
        $this->expectedParsedXmlString = '<?xml version="1.0" encoding="UTF-8"?>' .
            '<details>' .
            '<media>' .
            '<image>anImage.png</image>' .
            '<image>anotherImage.png</image>' .
            '<video>aVideo.mp4</video>' .
            '<audio/>' .
            '</media>' .
            '</details>';

        $this->expectedParsedXml = simplexml_load_string($this->expectedParsedXmlString);

        $this->utils = ArrayUtils::instance();
    }

    /** @test */
    public function arrayFlattenMakesArrayUnidimensional()
    {
        $expected = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
        $toFlat = array(1, 2, 3, array(4, 5, 6, array(7, 8, 9)));

        $result = $this->utils->flatten($toFlat);

        $this->assertSame($expected, $result);
    }

    /** @test */
    public function arrayFlattenMakesArrayUnidimensionalPreservingKeys()
    {
        $expected = array(3, 'a' => 1, 'b' => 2, 'c' => 3);
        $toFlat = array(1, 'a' => 1, 'b' => 1, 'c' => 1, array(2, 'b' => 2, 'c' => 2, array(3, 'c' => 3)));

        $result = $this->utils->flatten($toFlat, ArrayUtils::PRESERVE_KEYS);

        $this->assertSame($expected, $result);
    }

    /** @test */
    public function arrayFlattenMakesArrayUnidimensionalPreserveingAssociativeKeys()
    {
        $expected = array(1, 'a' => 1, 'b' => 2, 'c' => 3, 2, 3);
        $toFlat = array(1, 'a' => 1, 'b' => 1, 'c' => 1, array(2, 'b' => 2, 'c' => 2, array(3, 'c' => 3)));

        $result = $this->utils->flatten($toFlat, ArrayUtils::PRESERVE_ASSOCIATIVE_KEYS);

        $this->assertSame($expected, $result);
    }

    /** @test */
    public function arrayConvertsToXmlString()
    {
        $result = $this->utils->toXmlString($this->arrayToParseAsXml);

        $this->assertSame($this->expectedParsedXmlString, $result);
    }

    /** @test */
    public function arrayConvertsToSimpleXmlElement()
    {
        $result = $this->utils->toXml($this->arrayToParseAsXml);

        $this->assertEquals($this->expectedParsedXml, $result);
    }
}
