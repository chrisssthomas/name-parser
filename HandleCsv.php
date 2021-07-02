<?php
/** 
 * 
 * @package CSV-Parser
 * @author Christopher Thomas
*/


class HandleCsv
{
    /**
     * Convert the examples.csv file to an array
     * & remove the column header.
     * 
     * @return array $arr
     */
    public function csvToArray()
    {
        $arr = str_getcsv(file_get_contents('examples.csv'));

        array_shift($arr);

        var_dump($arr);


        return $arr;


    }

    public function parseNames()
    {
        $arr = $this->csvToArray();

        foreach($arr as $n) 
        {
            $r = [];

            preg_match('#^(\w+\.)?\s*([\'\’\w]+)\s+([\'\’\w]+)\s*(\w+\.?)?$#', $n, $r);

            print_r($r[1] ?? null);
        }
    }
}