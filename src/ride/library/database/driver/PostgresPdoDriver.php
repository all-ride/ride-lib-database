<?php

namespace ride\library\database\driver;

use ride\library\database\exception\DatabaseException;

use \PDOException;
use \PDO;

/**
 * PDO implementation of the database driver for PostgresSQL
 */
class PostgresPdoDriver extends PdoDriver {

    /**
     * Connects this connection
     * @return null
     * @throws \ride\library\database\exception\MysqlException when no
     * connection could be made with the host
     * @throws \ride\library\database\exception\MysqlException when the
     * database could not be selected
     */
    public function connect() {
        $protocol = $this->dsn->getProtocol();
        $host = $this->dsn->getHost();
        $port = $this->dsn->getPort();
        $username = $this->dsn->getUsername();
        $password = $this->dsn->getPassword();
        $database = $this->dsn->getDatabase();

        $dsn = $protocol . ':host=' . $host;
        if ($port) {
            $dsn .= ';port=' . $port;
        }
        $dsn .= ';username=' . $username;
        $dsn .= ';password=' . $password;
        $dsn .= ';dbname=' . $database;
        $dsn .= ';charset=utf8';

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_AUTOCOMMIT => false,
        );

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);

            if ($this->log) {
                $this->log->logDebug('Connected to ' . $protocol . '://' . $username . ':*****@' . $host . '/' . $database, null, self::LOG_SOURCE);
            }
        } catch (PDOException $exception) {
            $exception = new DatabaseException('Could not connect to ' . $protocol . '://' . $username . ':*****@' . $host . '/' . $database, 0, $exception);

            if ($this->log) {
                $this->log->logException($exception, self::LOG_SOURCE);
            }

            $this->pdo = null;

            throw $exception;
        }
    }

}