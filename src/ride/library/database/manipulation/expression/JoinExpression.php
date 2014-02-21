<?php

namespace ride\library\database\manipulation\expression;

use ride\library\database\exception\DatabaseException;
use ride\library\database\manipulation\condition\Condition;

/**
 * Table join definition
 */
class JoinExpression extends Expression {

    /**
     * Type for a LEFT JOIN
     * @var string
     */
    const TYPE_LEFT = 'LEFT';

    /**
     * Type for a INNER JOIN
     * @var string
     */
    const TYPE_INNER = 'INNER';

    /**
     * Type for a RIGHT JOIN
     * @var string
     */
    const TYPE_RIGHT = 'RIGHT';

    /**
     * The type of this join
     * @var string
     */
    private $type;

    /**
     * The table to join with
     * @var TableExpression
     */
    private $table;

    /**
     * The condition of this join expression
     * @var ride\library\database\manipulation\condition\Condition
     */
    private $condition;

    /**
     * Construct a new join expression
     * @param string $type join type (INNER, LEFT or RIGHT)
     * @param TableExpression $table table to join with
     * @param ride\library\database\manipulation\condition\Condition $condition
     * join condition
     * @return null
     */
    public function __construct($type, TableExpression $table, Condition $condition) {
        $this->setType($type);
        $this->table = $table;
        $this->condition = $condition;
    }

    /**
     * Set the join type
     * @param string $type possible values are INNER, LEFT and RIGHT
     * @return null
     * @throws ride\library\database\exception\DatabaseException when the type
     * is empty or not valid type
     */
    private function setType($type) {
        if (!is_string($type) || !$type) {
            throw new DatabaseException('Provided type is empty');
        }

        if ($type != self::TYPE_LEFT && $type != self::TYPE_INNER && $type != self::TYPE_RIGHT) {
            throw new DatabaseException($type . ' is not a valid type, try ' . self::TYPE_LEFT . ', ' . self::TYPE_INNER . ' or ' . self::TYPE_RIGHT);
        }

        $this->type = $type;
    }

    /**
     * Get the join type
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Get the table to join with
     * @return TableExpression
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Get the condition of this join
     * @return ride\library\database\manipulation\condition\Condition
     */
    public function getCondition() {
        return $this->condition;
    }

}