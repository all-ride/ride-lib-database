<?php

namespace ride\library\database\manipulation\statement;

use ride\library\database\manipulation\expression\TableExpression;

/**
 * Base class for a statement with tables
 */
abstract class TableStatement extends Statement {

    /**
     * Array with the table expression of this statement
     * @var array
     */
    protected $tables = array();

    /**
     * Adds a table to the statement
     * @param \ride\library\database\manipulation\expression\TableExpression $table
     * @return null;
     */
    public function addTable(TableExpression $table) {
        $alias = $table->getAlias();
        if ($alias) {
            $this->tables[$alias] = $table;
        } else{
            $this->tables[$table->getName()] = $table;
        }
    }

    /**
     * Gets the tables of this statement
     * @return array Array with TableExpression objects as value and the alias
     * or name as key
     */
    public function getTables() {
        return $this->tables;
    }

}