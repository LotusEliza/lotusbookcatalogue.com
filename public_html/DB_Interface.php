<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 1/25/19
 * Time: 3:03 PM
 */

interface DB_Interface
{
    /**
     * @param string $table
     * @param array $data
     * @param array $format
     * @return bool|int
     */
    public function insert($table, $data, $format);

    /**
     * @param string $table
     * @param array $data
     * @param array $format
     * @param array $where
     * @param array $where_format
     * @return bool|int
     */
    public function update($table, $data, $format, $where, $where_format);

    /**
     * @param string $query
     * @return resource
     */
    public function get_results($query);

    /**@param string $table
     * @param string $idname
     * @param int $id
     * @return bool|int
     */
    public function delete($table, $idname, $id);

    /**
     * @param $result
     * @return array
     */
    public function create_Object_Book($result);

    /**
     * @param $result
     * @return array
     */
    public function create_Object_Author($result);

    /**
     * @param $result
     * @return array
     */
    public function create_Object_Genre($result);

    /**
     * @param int $bookid
     * @param int $authorid
     * @return bool|int
     */
    public function deleteIJ($bookid, $authorid);

}
