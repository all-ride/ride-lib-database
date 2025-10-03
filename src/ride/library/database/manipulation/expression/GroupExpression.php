<?php

namespace ride\library\database\manipulation\expression;

class GroupExpression extends Expression{

    private $expression;


    public function __construct(Expression $expression) {
        $this->setExpression($expression);
    }

    /**
     * Set the expression of this order clause
     * @param Expression expression
     * @return null
     */
    private function setExpression(Expression $expression) {
        $this->expression = $expression;
    }

    /**
     * Get the expression of this order clause
     * @return Expression expression
     */
    public function getExpression() {
        return $this->expression;
    }
}