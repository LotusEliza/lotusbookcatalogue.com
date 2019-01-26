<?php

require_once ('DB_MySql.php');
require_once ('DB_PDO.php');
require_once('choose_DB_driver.php');


class Book
{

    public $bookid;
    public $title;
    public $description;
    public $price;

    function __construct($bookid, $title, $description, $price)
    {
        $this->bookid = $bookid;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
    }

    function __toString()
    {
        $output = "<h2>Book id: $this->bookid</h2>\n" .
            "<h2>Book title: $this->title</h2>\n" .
            "<h2>Description: $this->description</h2>\n" .
            "<h2>Price: $this->price</h2>\n";
        return $output;
    }


    function saveBook()
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->insert('books', array('title' => $this->title, 'description' => $this->description, 'price' => $this->price), array('%s', '%s', '%d')));
        return $result;
    }


    function updateBook()
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->update('books', array('title' => $this->title, 'description' => $this->description, 'price' => $this->price), array('%s', '%s', '%d'), array('bookid' => $this->bookid), array('%d')));
        return $result;
    }

    function removeBook($bookid)
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->delete('books', 'bookid', $bookid));
        return $result;
    }


    static function getBooksByAuthor($authorid)
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->get_results("SELECT b.* FROM books b INNER JOIN books_authors ba ON b.bookid=ba.bookid WHERE ba.authorid = $authorid"));
        $results = ($db->create_Object_Book($result));
        return $results;
    }


    static function getBooksByGenre($genreid)
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->get_results("SELECT b.* FROM books b INNER JOIN books_genres bg ON b.bookid=bg.bookid WHERE bg.genreid = $genreid"));
        $results = ($db->create_Object_Book($result));
        return $results;
    }


    static function getBook($bookid)
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->get_results("SELECT * FROM books WHERE bookid = $bookid"));
        $results = ($db->create_Object_Book($result));
        return $results;
    }


    static function getAllBooks()
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->get_results("SELECT books.bookid, books.title FROM books"));
        $result = ($db->create_Object_Book($result));
        return $result;
    }


    static function findBook($bookid)
    {
        global $driverType;
        $db = getDbConnection($driverType);
        $result = ($db->get_results("SELECT * FROM books WHERE bookid = $bookid"));
        $results = ($db->create_Object_Book($result));
        return $results;
    }
}


