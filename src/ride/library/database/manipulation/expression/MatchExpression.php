<?php

namespace ride\library\database\manipulation\expression;

use ride\library\database\exception\DatabaseException;

/**
 * Match expression for a statement
 */
class MatchExpression extends AliasExpression {

    /**
     * Name of the function
     * @var string
     */
    private $fields;

    /**
     * Array with Expression objects as arguments for the function
     * @var array
     */
    private $expression;

    /**
     * Flag for the distinct argument
     * @var boolean
     */
    private $modifier;

    /**
     * Construct a new function expression
     * @param string $name name of the function
     * @param string $alias alias for the result field
     */
    public function __construct(Expression $expression, $alias = null) {
        $this->expression = $expression;
        $this->fields = array();
        $this->setAlias($alias);
    }

    /**
     * Get the expression to match
     * @return Expression Expression to match
     */
    public function getExpression() {
        return $this->expression;
    }

    /**
     * Add a field to the function
     * @param Expression $field
     * @return null
     */
    public function addField(FieldExpression $field) {
        $this->fields[] = $field;
    }

    /**
     * Get the arguments for the function
     * @return array array with Expression objects
     */
    public function getFields() {
        return $this->fields;
    }

    public function setModifier($modifier) {
        $this->modifier = $modifier;
    }

    public function getModifier() {
        return $this->modifier;
    }

}
