<?php

namespace ride\library\database\definition\definer;

use ride\library\database\definition\Table;
use ride\library\database\driver\Driver;

/**
 * Interface for a database/table definer of a driver
 */
interface Definer {

    /**
     * Sets the connection to this definer
     * @param \ride\library\database\driver\Driver $connection
     * @return null
     */
    public function setConnection(Driver $connection);

    /**
     * Gets the connection of this definer
     * @return \ride\library\database\driver\Driver
     */
    public function getConnection();

    /**
     * Gets the table definition of a table
     * @param string $name Name of the table
     * @return \ride\library\database\definition\Table Table definition
     */
    public function getTable($name);

    /**
     * Defines a table in the connection with the given table definition. If
     * the table does not exist, it will be created. If the table structure is
     * different than the definition, it will be altered
     * @param \ride\library\database\definition\Table $table Table definition
     * @return null
     */
    public function defineTable(Table $table);

    /**
     * Defines the foreign keys for the provided table
     * @param \ride\library\database\definition\Table $table Table definition
     * @return null
     */
    public function defineForeignKeys(Table $table);

    /**
     * Drops a table from the connection if it exists
     * @param string $name Name of the table to drop
     * @return null
     */
    public function dropTable($name);

    /**
     * Gets a list of the available tables in the database
     * @return array Array with table names
     */
    public function getTableList();

    /**
     * Checks if a table exists
     * @param string $name Name of the table to check
     * @return boolean True if the table exists, false otherwise
     */
    public function tableExists($name);

    /**
     * Gets the predefined types for this definer
     * @return array Array with the name of the predefined type as key and the
     * database type as value
     */
    public function getFieldTypes();

}