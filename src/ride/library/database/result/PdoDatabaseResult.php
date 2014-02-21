<?php

namespace ride\library\database\result;

use \PDO;
use \PDOStatement;

/**
 * PDO implementation of a database result
 */
class PdoDatabaseResult extends DatabaseResult {

    /**
     * Constructs a new PDO result
     * @param string $sql SQL which generated this result
     * @param PDOStatement $statement Executed PDO statement
     * @return null
     */
    public function __construct($sql, PDOStatement $statement) {
        parent::__construct($sql);

        $startsWith = strtoupper(substr($sql, 0, 5));
        if ($startsWith != 'SELEC' && $startsWith != 'SHOW ') {
            return;
        }

        $this->initializeResult($statement);
    }

    /**
     * Initializes this result from the resource of a MySQL result
     * @param PDOStatement $statement Executed PDO statement
     * @return null
     */
    private function initializeResult(PDOStatement $statement) {
        $isColumnsSet = false;

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (!$isColumnsSet) {
                foreach ($row as $columnName => $value) {
                    $this->columns[] = $columnName;
                }

                $isColumnsSet = true;
            }

            $this->rows[] = $row;
        }

        $this->columnCount = count($this->columns);
        $this->rowCount = count($this->rows);
    }

}