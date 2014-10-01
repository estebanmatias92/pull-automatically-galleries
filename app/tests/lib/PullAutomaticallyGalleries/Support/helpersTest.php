<?php

class HelpersTest extends TestCase
{
    public function testArraySeparateWithStringKeys()
    {
        $array = [
            'id'          => 1,
            'name'        => 'John Doe',
            'email'       => 'john@doe.com',
            'phone'       => '000 0000 0000',
            'description' => 'I dont know',
        ];
        $keys = ['name', 'description'];
        $separatedFromArray = [
            'name'        => 'John Doe',
            'description' => 'I dont know',
        ];
        $expectedArray = [
            'id'    => 1,
            'email' => 'john@doe.com',
            'phone' => '000 0000 0000',
        ];

        $result = array_separate($array, $keys);

        assertThat($result, is(equalTo($separatedFromArray)));
        assertThat($array, is(equalTo($expectedArray)));

    }

    public function testArraySeparateWithIntegerKeys()
    {
        $array = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
        ];
        $keys = [4, 2];
        $separatedFromArray = [
            4 => 'April',
            2 => 'February',
        ];
        $expectedArray = [
            1 => 'January',
            3 => 'March',
        ];

        $result = array_separate($array, $keys);

        assertThat($result, is(equalTo($separatedFromArray)));
        assertThat($array, is(equalTo($expectedArray)));

    }

    public function testArraySeparateWithUndefinedIndexes()
    {
        $array = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
        ];
        $keys = [5, 6];
        $result = array_separate($array, $keys);

        assertThat($result, is(emptyArray()));
        assertThat(count($array), is(equalTo(4)));

    }

    public function testArrayMeshReturnsArray()
    {
        $array = [
            'hours' => 1,
        ];
        $arrayB = [
            'hours' => 2,
        ];

        $result = array_mesh($array, $arrayB);

        assertThat($result, is(arrayValue()));

    }

    public function testArrayMeshAddsCorrectly()
    {
        $array = [
            'minutes' => 100,
            'seconds' => 7000,
        ];
        $arrayB = [
            'hours'   => 2,
            'minutes' => 20,
            'seconds' => 200,
        ];
        $expectedResult = [
            'hours'   => 2,
            'minutes' => 120,
            'seconds' => 7200,
        ];

        $result = array_mesh($array, $arrayB);

        assertThat($result, is(equalTo($expectedResult)));

    }

    public function testExplodeAssocReturnsArray()
    {
        $string = 'some_key=dummyvalue&another_key=anothervalue';

        $explodedValue = explode_assoc($string);

        assertThat($explodedValue, is(arrayValue()));;

    }

    public function testExplodeAssocReturnsCorrectArrayValue()
    {
        $string        = 'some_key=dummyvalue&another_key=anothervalue';
        $expectedArray = [
            'some_key'    => 'dummyvalue',
            'another_key' => 'anothervalue'
        ];

        $explodedValue = explode_assoc($string);

        assertThat($explodedValue, is(equalTo($expectedArray)));

    }

    public function testExplodeAssocWithCustomGlueArgumentsReturnsCorrectArrayValue()
    {
        $string        = 'some_key:dummyvalue;another_key:anothervalue';
        $expectedArray = [
            'some_key'    => 'dummyvalue',
            'another_key' => 'anothervalue'
        ];

        $explodedValue = explode_assoc($string, ':', ';');

        assertThat($explodedValue, is(equalTo($expectedArray)));

    }

}
