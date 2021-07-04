<?php
/** 
 * 
 * Given an array of peoples names in strings,
 * this class will parse and build a person array
 * with the following structure, even if there are 
 * more than one person per field.
 * 
 * e.g. 
 * $parser = new ParseName;
 * $parser->makePeople();
 * 
 * $person['title']
 * $person['first_name']
 * $person['initial']
 * $person['last_name']
 * 
 * 
 * @package Name Parser
 * @author Christopher Thomas
*/

class ParseName
{
    // Regex for name titles, e.g. Miss, Mr, Mrs, Ms, Mister, Prof, Dr
    public const TITLE_REGEX = '^(Miss|Mr|Mrs|Ms|Mister|Prof|Dr)\b^';
    // Regex for basic coordinating conjunctions, e.g. &, and
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
     * array is more than 1 (more than 1 person) assign the
     * corrosponding person the correct last name.
     * 
     * It goes without saying all of the regex's in this implemenation
     * could be expanded upon for more complex data sets.
     * 
     *
     * @return array $full_list
     */
    public function findPairs()
    {
        $arr = $this->csvToArray();

        foreach($arr as $n) 
        {
            if (preg_match(self::AND_REGEX, $n)) {

                $split = preg_split(self::AND_REGEX, $n);

                unset($n);

                switch ($split) {
                    case count($split) > 1 && str_word_count($split[0]) === 1:
                        $last_name = $this->findLastName($split[1]);

                        $combined_names[] = $split[1];
    
                        $combined_names[] = $split[0] .= $last_name;
    
                        break;
                    case count($split) > 1 && str_word_count($split[0]) !== 1:
                        $full_names = $split;
                        break;
                }
            }
            if(!empty($n)) {
                $singular_names[] = $n;
            }
        }

        $combined_singular_and_combined = array_merge($combined_names, $singular_names);

        $merged_array = array_merge($combined_singular_and_combined, $full_names);

        $full_list = array_map('trim', array_filter(str_replace(PHP_EOL, '', $merged_array)));

        return $full_list;
    }

    /**
     * Build the person Array, first by calling findPairs() method
     * match the name with the TITLE_REGEX and find the given last name.
     * Proceed to build the person array based on the remaining word count.
     * 
     * @return array $people
     */
    public function makePeople()
    {
        $arr = $this->findPairs();

        $people = [];

        foreach($arr as $n) {
            
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
            } elseif (str_word_count($n) <= 2) {
                $person['first_name'] = null;
                $person['initial'] = null;
            }

            $person['last_name'] = $last_name;

            $people[] = $person;
        }

        return $people;
    }

    /**
     * Check whether the given name is an initial.
     *
     * @param string $name
     * @return boolean
     */
    public function isInitial($name)
    {
        $trim_periods = rtrim($name, '.');

        $str_count = array_count_values(str_split($trim_periods));

        if (count($str_count) === 1) {
            return true;
        }
        return false;
    }

    /**
     * Find the last name or word in a given string.
     *
     * @param string $name
     * @return string $last_name
     */
    public function findLastName($name)
    {
        $pieces = explode(' ', $name);
        $last_name = array_pop($pieces);
        return $last_name;
    }
}