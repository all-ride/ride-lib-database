<?php

namespace ride\library\database\definition;

use ride\library\database\exception\DatabaseException;

/**
 * Definition of an index of a table
 */
class Index {

    /**
     * Name of this index
     * @var string
     */
    private $name;

    /**
     * Array with the fields of this index
     * @var array
     */
    private $fields;

    /**
     * Construct a new index
     * @param string name name of the index
     * @param array $fields Array with the field definitions for the index
     * @return null
     * @see Field
     */
    public function __construct($name, array $fields) {
        $this->setName($name);
        $this->setFields($fields);
    }

    /**
     * Set the name of this index
     * @param string name name of this index
     * @throws ride\library\database\exception\DatabaseException when no valid string provided as name
     * @throws ride\library\database\exception\DatabaseException when the name
     * of the index is empty
     */
    private function setName($name) {
        if (!is_string($name) || !$name) {
            throw new DatabaseException('Name is empty');
        }

        $this->name = $name;
    }

    /**
     * Get the name of this index
     * @return string name of this index
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the fields for this index
     * @param array fields array with Field instances
     * @return null
     * @throws ride\library\database\exception\DatabaseException when an empty
     * array is provided for the fields or when there is a non Field instance
     * in the array
     */
    private function setFields(array $fields) {
        if (empty($fields)) {
            throw new DatabaseException('No fields provided for this index');
        }

        $this->fields = array();
        foreach ($fields as $index => $field) {
            if (!$field instanceof Field) {
                throw new DatabaseException('Provided fields does not contain a Field instance on index ' . $index);
            }

            $this->fields[$field->getName()] = $field;
        }
    }

    /**
     * Get the fields for this index
     * @return array Array with Field instances
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Checks if the provided index has the same fields as this index
     * @param Index $index Index to check
     * @return boolean True when the provided index has the same fields, false
     * otherwise
     */
    public function equals(Index $index) {
        $fields = $index->getFields();

        foreach ($fields as $fieldName => $field) {
            if (!isset($this->fields[$fieldName])) {
                return false;
            }
        }

        foreach ($this->fields as $fieldName => $field) {
            if (!isset($fields[$fieldName])) {
                return false;
            }
        }

        return true;
    }

}