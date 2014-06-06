<?php

namespace ride\library\database\manipulation;

use ride\library\database\manipulation\statement\Statement;

/**
 * Parser to translate Statement objects into sql
 */
interface StatementParser {

    /**
     * Parses a statement into sql
     * @param \ride\library\database\manipulation\statement\Statement $statement
     * @return string SQL for the provided statement
     */
    public function parseStatement(Statement $statement);

}