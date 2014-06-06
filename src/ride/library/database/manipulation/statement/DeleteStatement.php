<?php

namespace ride\library\database\manipulation\statement;

use ride\library\database\exception\DatabaseException;
use ride\library\database\manipulation\expression\TableExpression;

/**
 * Statement to delete records from a table
 */
class DeleteStatement extends ConditionalStatement {

    /**
     * Adds a table this delete statement (only 1 table allowed)
     * @param \ride\library\database\manipulation\expression\TableExpression $table
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when a table
     * has already been added
     */
    public function addTable(TableExpression $table) {
        if (count($this->tables)) {
            throw new DatabaseException('Only deletes on 1 table allowed');
        }

        parent::addTable($table);
    }

}