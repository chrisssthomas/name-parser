# Name Parser

Given an array of peoples names in strings,
this class will parse and build a person array
with the following structure, even if there are
more than one person per field.

e.g.

Usage

```
public function csvToArray()
{
    $arr = str_getcsv(file_get_contents('yourfile.csv'));
}
```

Object instantiation

```
$parser = new ParseName;
$parser->makePeople();
```

Array structure

```
$person['title']
$person['first_name']
$person['initial']
$person['last_name']
```
