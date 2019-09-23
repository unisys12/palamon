<?php

namespace Palamon\Destiny2\DB;

use PDO;
use SQLite;

/**
 * Interacts with the decompressed SQLite Database for Destiny 2
 * 
 * @author unisys12 <unisys12@gmail.com>
 */

class Content
{
    /**
     * Requires a path to be passed, as a string, that points to 
     * the location of the SQLite DB
     *
     * @param string $path path to the SQLite DB
     */
    public function __construct($path)
    {
        $this->path = $path;
        // $this->open($this->path);
    }

    /**
     * Makes connection to SQLite file
     * 
     * @return resource
     */
    private function _conn()
    {
        // SQLite3::open($this->path, SQLITE3_OPEN_READONLY);
        return new PDO('sqlite:' . $this->path);
    }

    /**
     * Queries SQLite file and returns each table it contains
     * 
     * @return array
     */
    public function getTableNames()
    {
        $db = self::_conn();

        $results = $db->query("SELECT * FROM main.sqlite_master WHERE type='table'", PDO::FETCH_COLUMN, 1);

        foreach ($results as $table) {
            $tableNames[] = $table;
        }

        return $tableNames;
    }

    /**
     * Gets the Contents of a single table
     *
     * @param  string $tableName The name of the table you want to query
     * @return array
     */
    public function getTableContents($tableName)
    {
        $db = self::_conn();

        $results = $db->query("SELECT * FROM " . $tableName);

        foreach ($results as $row) {
            $tableContents[] = json_decode($row[1]);
        }

        return $tableContents;

    }


}