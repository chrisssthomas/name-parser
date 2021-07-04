<?php
/** 
 * 
 * 
 * TODO: Parse initial
 * TODO: Parse first names
 * TODO: Parse last name (started) DONE
 * TODO: Parse title (started) DONE
 * 
 * 
 * @package CSV-Parser
 * @author Christopher Thomas
*/

class ParseName
{

    // Regex for name titles
    public const TITLE_REGEX = '^(Miss|Mr|Mrs|Ms|Mister|Prof|Dr)\b^';
    public const AND_REGEX = '/&(?!(?:apos|quot|[gl]t|amp);|#)|(and)/';

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

        return $arr;
    }

    /**
     * Split by the AND_REGEX and if the count of the
     * array is more than 1 (more than 1 person) assign that
     * person the correct last name.
     * 
     *
     * @return array $fullnames
     */
    public function findPairs()
    {
        $arr = $this->csvToArray();

        $combined_names = [];

        $other_names = [];

        foreach($arr as $n) 
        {
            if (preg_match(self::AND_REGEX, $n)) {

                $split = preg_split(self::AND_REGEX, $n);

                unset($n);

                if(count($split) > 1 && str_word_count($split[0]) === 1)
                {
                    $last_name = $this->findLastName($split[1]);

                    $joined_names = $split[0] .= $last_name;

                    $fullnames = $split[1];

                    $combined_names[] = $fullnames;

                    $combined_names[] = $joined_names;

                } elseif(count($split) > 1 && str_word_count($split[0]) !== 1) {

                    $combined_names = $split;
                }
            }

            $other_names[] = $n ?? null;
        }
        $combined_list = array_merge($combined_names, $other_names);

        $full_list = array_map('trim', array_filter(str_replace(PHP_EOL, '', $combined_list)));

        return $full_list;
    }

    /**
     * Find titles
     * 
     * @return array $arr
     */
    public function buildPerson()
    {
        $arr = $this->findPairs();

        foreach($arr as $n) 
        {
            preg_match(self::TITLE_REGEX, $n, $r);

            $last_name = $this->findLastName($n);

            $person['title'] = $r[0];

            if (str_word_count($n) > 2) {
                $split = explode(' ', $n);

                if ($this->isInitial($split[1])) {
                    $person['initial'] = $split[1];
                    $person['first_name'] = null;
                } else {
                    $person['initial'] = null;
                    $person['first_name'] = $split[1];
                }
            }
            $person['last_name'] = $last_name;

            echo '<pre>';
            print_r($person);
            echo '</pre>';
        }
    }

    public function isInitial($name)
    {
        $trim_periods = rtrim($name, '.');
        $test = array_count_values(str_split($trim_periods));

        if (count($test) === 1) {
            return true;
        }
        return false;
    }

    public function findLastName($name)
    {
        $pieces = explode(' ', $name);
        $last_word = array_pop($pieces);

        return $last_word;
    }
}