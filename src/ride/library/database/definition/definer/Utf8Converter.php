<?php

namespace ride\library\database\definition\definer;

/**
 * Interface to convert tables to UTF-8
 */
interface Utf8Converter {

    /**
     * Converts all tables in this database to UTF8
     * @return null
     * @throws ride\library\database\exception\DatabaseException when an error
     * occured
     */
    public function convertDatabaseToUTF8();

    /**
     * Converts the provided table to UTF8
     * @param string $name Name of the table
     * @return null
     * @throws ride\library\database\exception\DatabaseException when the table
     * does not exist
     */
    public function convertTableToUTF8($name);

}