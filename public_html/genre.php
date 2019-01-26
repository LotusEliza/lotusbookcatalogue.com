<?php

class Genre{

    public $genreid;
    public $genre;

    function __construct($genreid, $genre)
    {
        $this->genreid=$genreid;
        $this->genre = $genre;
    }


    function __toString()
    {
        $output = "<h2>Genre ID: $this->genreid</h2>\n" .
                  "<h2>Genre : $this->genre</h2>\n";
        return $output;
    }


    static function getGenres()
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->get_results("SELECT * FROM genres"));
        $results = ($db -> create_Object_Genre($result));
        return $results;
    }


    function saveGenre()
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->insert('genres', array('genre' => $this->genre), array('%s')));
        return $result;
    }


    static function saveBooks_Genres($newbookid, $newgenreid)
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->insert('books_genres', array('bookid' => $newbookid, 'genreid' => $newgenreid), array('%d', '%d')));
        return $result;
    }
}