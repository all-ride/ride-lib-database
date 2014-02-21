<?php

namespace ride\library\database\manipulation\expression;

use ride\library\database\exception\DatabaseException;

/**
 * Field definition to use in statements
 */
class FieldExpression extends AliasExpression {

    /**
     * Name of the field
     * @var string
     */
    private $name;

    /**
     * Table of the field
     * @var TableExpression
     */
    private $table;

    /**
     * Construct a new field expression
     * @param string $name name of the field
     * @param TableExpression $table table of the field (optional)
     * @param string $alias alias for the field (optional)
     * @return null
     */
    public function __construct($name, TableExpression $table = null, $alias = null) {
        $this->setName($name);
        $this->setAlias($alias);
        $this->table = $table;
    }

    /**
     * Set the name of this field
     * @param string $name
     * @return null
     * @throws ride\library\database\exception\DatabaseException when the name
     * is empty or not a string
     */
    private function setName($name) {
        if (!is_string($name) || !$name) {
            throw new DatabaseException('Provided name is empty');
        }

        $this->name = $name;
    }

    /**
     * Get the name of this field
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get the table of this field
     * @return TableExpression
     */
    public function getTable() {
        return $this->table;
    }

}