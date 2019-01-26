<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 1/25/19
 * Time: 1:29 PM
 */

require_once ('config.php');
require_once ('DB_Abstract.php');


class DB_MySql extends DB_Abstract
{

    public function connect()
    {
        return new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }


    public function query($query)
    {
        $db = $this->connect();
        $result = $db->query($query);

        return $result;
    }


    public function insert($table, $data, $format)
    {
        if(empty($table) || empty($data)){
            return false;
        }

        $db = $this->connect();

        //Cast $data and format to arrays
        $data = (array) $data;
        $format = (array) $format;

        //Build format string (%s %d...)
        $format = implode('', $format); //return string
        $format = str_replace('%', '', $format); //get rid of %

        list($fields, $placeholders, $values) = $this->prep_query($data);

        array_unshift($values, $format);

        //Prepare our query for binding
        $stmt = $db->prepare("INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})");

        //Dynamically bind values
        call_user_func_array(array($stmt, 'bind_param'), $this->ref_values($values));

        //Execute the query
        if ($stmt->execute()) {
            $newid = $db->insert_id;
            echo "New record to table $table created successfully. Last inserted ID is: " . $newid."<br>";
            return $newid;
        }

        return false;
    }


    public function update($table, $data, $format, $where, $where_format)
    {
        if(empty($table) || empty($data)){
            return false;
        }
        $db = $this->connect();

        $data = (array) $data;
        $format = (array) $format;

        $format = implode('', $format);
        $format = str_replace('%', '', $format);

        $where_format = implode('', $where_format);
        $where_format = str_replace('%', '', $where_format);
        $format.=$where_format;

        list($fields, $placeholders, $values) = $this->prep_query($data, 'update');

        $where_clause = '';
        $where_values = '';
        $count = 0;

        foreach ($where as $field => $value){
            if($count > 0){
                $where_clause .= ' AND ';
            }

            $where_clause .=$field . '=?';
            $where_values[] = $value;

            $count++;
        }

        array_unshift($values, $format);
        $values = array_merge($values, $where_values);

        $stmt = $db->prepare("UPDATE {$table} SET {$placeholders} WHERE {$where_clause}");

        call_user_func_array( array($stmt, 'bind_param'), $this->ref_values($values));

        $stmt->execute();

        if($stmt->affected_rows) {
            return true;
        }
        return false;
    }


    public function get_results($query)
    {
        $results = $this->query($query);
        return $results;
    }


    public function get_row($query)
    {
        $results = $this->query($query);

        return  $results[0];
    }


    public function delete($table, $idname, $id)
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM {$table} WHERE {$idname} = ?");
        $stmt ->bind_param('d', $id);
        $stmt->execute();

        if($stmt->affected_rows) {
            return true;
        }
        return false;
    }


    private function prep_query($data, $type = 'insert')
    {
        $fields = '';
        $placeholders = '';
        $values = array();

        foreach ($data as $field=>$value) {
            $fields .= "{$field},";
            $values[] = $value;
            if($type == 'update'){
                $placeholders .= $field . '=?,';
            }else{
                //creating placeholders for value section in a query string
                $placeholders .='?,';
            }
        }

        $fields = substr($fields, 0, -1);
        $placeholders = substr($placeholders, 0, -1);

        return array($fields, $placeholders, $values);
    }


    private function ref_values($array)
    {
        $refs = array();
        foreach ($array as $key => $value){
            $refs[$key] = &$array[$key];
        }
        return $refs;
    }


    public function create_Object_Book($result)
    {
        if (mysqli_num_rows($result) > 0) {
            $books = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $book = new Book($row['bookid'], $row['title'], $row['description'], $row['price']);
                array_push($books, $book);
                unset($book);
            }
            return $books;
        }
    }


    public function create_Object_Author($result)
    {
        if (mysqli_num_rows($result) > 0) {
            $authors = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $author = new Author($row['authorid'], $row['authorname']);
                array_push($authors, $author);
                unset($author);
            }
            return $authors;
        }
    }


    public function create_Object_Genre($result)
    {
        if (mysqli_num_rows($result) > 0) {
            $genres = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $genre = new Genre($row['genreid'], $row['genre']);
                array_push($genres, $genre);
                unset($genre);
            }
            return $genres;
        }
    }


    public function deleteIJ($bookid, $authorid)
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE a FROM books_authors a
                       INNER JOIN books b ON a.bookid=b.bookid
                       WHERE a.bookid = ? AND a.authorid= ?");

        $stmt ->bind_param('dd', $bookid,$authorid);
        $stmt->execute();

        if($stmt->affected_rows) {
            return true;
        }
        return false;
    }


    public function checkRemov($result, $record, $name)
    {
        if ($result===TRUE){
            echo "Record # $record - \"$name\" removed!\n";
        } else{
            echo "<h2>Sorry, problem removing $record</h2>\n";
        }
    }
}