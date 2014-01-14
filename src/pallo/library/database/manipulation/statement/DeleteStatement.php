<?php

namespace pallo\library\database\manipulation\statement;

use pallo\library\database\exception\DatabaseException;
use pallo\library\database\manipulation\expression\TableExpression;

/**
 * Statement to delete records from a table
 */
class DeleteStatement extends ConditionalStatement {

    /**
     * Adds a table this delete statement (only 1 table allowed)
     * @param pallo\library\database\manipulation\expression\TableExpression $table
     * @return null
     * @throws pallo\library\database\exception\DatabaseException when a table
     * has already been added
     */
    public function addTable(TableExpression $table) {
        if (count($this->tables)) {
            throw new DatabaseException('Only deletes on 1 table allowed');
        }

        parent::addTable($table);
    }

}