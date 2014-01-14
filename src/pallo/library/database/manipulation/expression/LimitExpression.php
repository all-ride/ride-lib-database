<?php

namespace pallo\library\database\manipulation\expression;

use pallo\library\database\exception\DatabaseException;

/**
 * Limit clause of a statement
 */
class LimitExpression extends Expression {

    /**
     * Number of rows to show
     * @var int
     */
    private $rowCount;

    /**
     * Offset of the limitation, starting position
     * @var int
     */
    private $offset;

    /**
     * Construct the limit clause
     * @return null
     */
    public function __construct($rowCount, $offset = null) {
        $this->setRowCount($rowCount);
        $this->setOffset($offset);
    }

    /**
     * Set the row count of this limit clause
     * @param int $rowCount
     * @return null
     * @throws pallo\library\database\exception\DatabaseException when $rowCount is not numeric
     * @throws pallo\library\database\exception\DatabaseException when $rowCount
     * is negative
     */
    private function setRowCount($rowCount) {
        if (!is_integer($rowCount) || $rowCount <= 0) {
            throw new DatabaseException('Provided row count should be a positive integer');
        }

    	$this->rowCount = $rowCount;
    }

    /**
     * Get the row count of this limit clause
     * @return int rowCount
     */
    public function getRowCount() {
        return $this->rowCount;
    }

    /**
     * Set the offset of this limit clause
     * @param int $offset
     * @return null
     * @throws pallo\library\database\exception\DatabaseException when $offset is not numeric
     * @throws pallo\library\database\exception\DatabaseException when $offset
     * is invalid
     */
    private function setOffset($offset = null) {
        if ($offset !== null && (!is_integer($offset) || $offset < 0)) {
            throw new DatabaseException('Provided offset should be a positive integer or zero');
        }

        $this->offset = $offset;
    }

    /**
     * Get the offset of the limitation
     * @return int offset
     */
    public function getOffset() {
        return $this->offset;
    }

}