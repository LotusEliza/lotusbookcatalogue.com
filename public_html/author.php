<?php

class Author
{

    public $authorid;
    public $authorname;

    function __construct($authorid, $authorname)
    {
        $this->authorid=$authorid;
        $this->authorname = $authorname;
    }

    function __toString()
    {
        $output = "<h2>Author ID: $this->authorid</h2>\n" .
                  "<h2>Author Name $this->authorname</h2>\n";
        return $output;
    }


    static function getAuthors()
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->get_results("SELECT * FROM authors"));
        $results = ($db -> create_Object_Author($result));
        return $results;
    }


    function saveAuthor(){
         global $driverType;
         $db = getDbConnection($driverType);
         $result = ($db->insert('authors', array('authorname' => $this->authorname), array('%s')));
         return $result;
    }


    static function saveBooks_Authors($newbookid, $newauthorid){
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->insert('books_authors', array('bookid' => $newbookid, 'authorid' => $newauthorid), array('%d', '%d')));
        return $result;
    }


    static function getAuthorsByBook($bookid)
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db -> get_results("SELECT a.* FROM authors a INNER JOIN books_authors ba ON a.authorid=ba.authorid WHERE ba.bookid = $bookid"));
        $results = ($db -> create_Object_Author($result));
        return $results;
    }


    function removeAuthor($bookid)
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->deleteIJ($bookid, $this->authorid));
        $db->checkRemov($result, $this->authorid, $this->authorname);
        return $result;
    }


    static function findAuthorByName($authorname)
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->get_results("SELECT * FROM authors WHERE authorname = '$authorname'"));
        $results = ($db -> create_Object_Author($result));
        return $results;
    }
}