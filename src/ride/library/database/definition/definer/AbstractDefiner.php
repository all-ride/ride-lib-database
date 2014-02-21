<?php

namespace ride\library\database\definition\definer;

use ride\library\database\exception\DatabaseException;
use ride\library\database\definition\Field;
use ride\library\database\definition\Table;
use ride\library\database\driver\Driver;

/**
 * Abstract database/table definer
 */
abstract class AbstractDefiner implements Definer {

    /**
     * Connection of this definer
     * @var ride\library\database\driver\Driver
     */
    protected $connection;

    /**
     * Array with the predefined field types
     * @var array
     */
    protected $fieldTypes;

    /**
     * Constructs a new definer
     * @return null
     */
    public function __construct() {
        $this->fieldTypes = array();
    }

    /**
     * Sets the connection to this definer
     * @param ride\library\database\driver\Driver $connection
     * @return null
     */
    public function setConnection(Driver $connection) {
        $this->connection = $connection;
    }

    /**
     * Gets the connection of this definer
     * @return ride\library\database\driver\Driver
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Defines a table in the connection with the given table definition. If
     * the table does not exist, it will be created. If the table structure is
     * different then the definition, it will be altered
     * @param ride\library\database\definition\Table $table Table definition
     * @return null
     */
    public function defineTable(Table $table) {
        if ($this->tableExists($table->getName())) {
            $this->alterTable($table);
        } else {
            $this->createTable($table);
        }
    }

    /**
     * Alters the table in the connection with the given table definition
     * @param ride\library\database\definition\Table $table Table definition
     * @return null
     */
    abstract protected function alterTable(Table $table);

    /**
     * Creates a new table in the connection
     * @param ride\library\database\definition\Table $table Table definition of
     * the table to create
     * @return null
     */
    abstract protected function createTable(Table $table);

    /**
     * Drop a table from the connection if it exists
     * @param string $name name of the table to drop
     * @return null
     * @throws ride\library\database\Exception\DatabaseException when the name
     * is empty or not a string
     */
    public function dropTable($name) {
        $this->validateName($name);

        $sql = 'DROP TABLE IF EXISTS ' . $this->connection->quoteIdentifier($name);

        $this->connection->execute($sql);
    }

    /**
     * Checks if a table exists
     * @param string $name name of the table to check
     * @return boolean true if the table exists, false otherwise
     * @throws ride\library\database\Exception\DatabaseException when the name
     * is empty or not a string
     */
    public function tableExists($name) {
        $this->validateName($name);

        $sql = 'SHOW TABLES LIKE ' . $this->connection->quoteValue($name);

        $result = $this->connection->execute($sql);

        return $result->getRowCount() == 0 ? false : true;
    }

    /**
     * Gets a list of the tables in the database connection
     * @return array Array with table names
     */
    public function getTableList() {
        $sql = 'SHOW TABLES';

        $result = $this->connection->execute($sql);

        $key = 'Tables_in_' . $this->connection->getDsn()->getDatabase();
        $tables = array();
        foreach ($result as $row) {
            $tables[] = $row[$key];
        }

        return $tables;
    }

    /**
     * Sets the predefined types for this definer
     * @param array $fieldTypes Array with the name of the predefined type as
     * key and the database type as value
     * @return null
     */
    public function setFieldTypes(array $fieldTypes) {
        $this->fieldTypes = $fieldTypes;
    }

    /**
     * Gets the predefined types for this definer
     * @return array Array with the name of the predefined type as key and the
     * database type as value
     */
    public function getFieldTypes() {
        return $this->fieldTypes;
    }

    /**
     * Translates a database layer's field type to a mysql field type
     * @param string|ride\library\database\definition\Field $field field can be
     * a database layer's type or a Field object
     * @return string mysql field type
     * @throws ride\library\database\exception\DatabaseException when no type
     * found for the provided field or type
     */
    protected function getFieldType($field) {
        $fieldTypes = $this->getFieldTypes();

        if ($field instanceof Field) {
            $fieldType = $field->getType();
            if (!isset($fieldTypes[$fieldType])) {
                throw new DatabaseException('No database type found for type ' . $fieldType);
            }

            return $fieldTypes[$fieldType];
        }

        $type = array_search($field, $fieldTypes);
        if ($type === false) {
            throw new DatabaseException('No type found for database type ' . $field);
        }

        return $type;
    }

    /**
     * Gets the default value of a field
     * @param Field $field
     * @return string SQL of the default value
     */
    protected function getDefaultValue(Field $field) {
        $default = $field->getDefaultValue();

        if ($default == null || strtoupper($default) == 'NULL') {
            return 'NULL';
        }

        return $this->connection->quoteValue($default);
    }

    /**
     * Checks if a name is a string and not empty
     * @param string $name
     * @return null
     * @throws ride\library\database\Exception\DatabaseException when the name
     * is empty or not a string
     */
    protected function validateName($name) {
        if (!is_string($name) || !$name) {
            throw new DatabaseException('Provided name is empty or invalid');
        }
    }

}