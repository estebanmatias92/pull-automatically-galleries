<?php

if (! function_exists('array_separate')) {

    /**
     * Returns the passed keys in a new array, and removes them from the original array.
     *
     * @param  array  $array
     * @param  array  $keys
     * @return array
     */
    function array_separate(array &$array, array $keys)
    {
        $separated = array();

        foreach ($keys as $key) {

            if (isset($array[$key])) {

                $separated[$key] = $array[$key];

                unset($array[$key]);

            }

        }

        return $separated;
    }

}

if (! function_exists('array_mesh')) {

    /**
     * Returns an array with values summed from same keys of different arrays.
     *
     * @return array
     */
    function array_mesh()
    {
        $argNum  = func_num_args();
        $argList = func_get_args();
        $out     = array();

        for ($i = 0; $i < $argNum; $i++) {

            $in = $argList[$i];

            foreach($in as $key => $value) {

                if(array_key_exists($key, $out)) {

                    $sum = $in[$key] + $out[$key];

                    $out[$key] = $sum;

                }else{

                    $out[$key] = $in[$key];

                }

            }

        }

        return $out;
    }
}

if (! function_exists('explode_assoc'))
{
    /**
     * It separes an string into associative array.
     *
     * @param  string $string
     * @param  string $keyGlue
     * @param  string $valueGlue
     * @return array
     */
    function explode_assoc($string, $keyGlue = '=', $valueGlue = '&')
    {
        $explodedArray = explode($valueGlue, $string);
        $newArray      = array();

        array_walk($explodedArray, function($value) use($keyGlue, &$newArray)
        {
            $explodedValue               = explode($keyGlue, $value);
            $newArray[$explodedValue[0]] = $explodedValue[1];
        });

        return $newArray;
    }
}
