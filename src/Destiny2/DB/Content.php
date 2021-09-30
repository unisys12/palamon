<?php

namespace Palamon\Destiny2\DB;

use Exception;
use PDO;
use SQLite;

/**
 * Interacts with the decompressed SQLite Database for Destiny 2
 * AKA Mobile World Content File
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
    public function __construct(public string $path)
    {
        //
    }

    /**
     * Makes connection to SQLite file
     *
     * @return object
     */
    private function conn()
    {
        return new PDO('sqlite:' . $this->path);
    }

    /**
     * Queries SQLite file and returns each table it contains
     *
     * @return array
     */
    public function getTableNames()
    {
        $db = $this->conn();

        $results = $db->query("SELECT * FROM main.sqlite_master WHERE type='tablle'", PDO::FETCH_COLUMN, 1);

        foreach ($results as $table) {
            $tableNames[] = $table;
        }

        return $tableNames ??= print "Error with query to the contentfile: $results->queryString";
    }

    /**
     * Gets the Contents of a single table
     *
     * @param string $tableName The name of the table you want to query
     *
     * @return array
     */
    public function getTableContents(string $tableName)
    {
        $db = $this->conn();

        $results = $db->query("SELECT * FROM " . $tableName);

        foreach ($results as $row) {
            $tableContents[] = json_decode($row[1]);
        }

        // @todo actually throw an exception. Throwing new Exception still returns a uncaught expection
        return $tableContents ??= print "Error with query to the contentfile: $results->queryString";
    }
}
