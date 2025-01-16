# Ride: Database Library

Database abstraction library of the PHP Ride framework.

## Code Sample

Check this code sample to see some of the possibilities of this library:

```php
<?php

use ride\library\database\driver\Driver;
use ride\library\database\exception\DatabaseException;
use ride\library\database\manipulation\condition\SimpleCondition;
use ride\library\database\manipulation\expression\FieldExpression;
use ride\library\database\manipulation\expression\ScalarExpression;
use ride\library\database\manipulation\expression\TableExpression;
use ride\library\database\manipulation\statement\SelectStatement;
use ride\library\database\DatabaseManager;
use ride\library\database\Dsn;
use ride\library\log\Log;

function createDatabaseManager(Log $log) {
    $databaseManager = new DatabaseManager();
    $databaseManager->setLog($log);
    $databaseManager->registerDriver('mysql', 'ride\\library\\database\\driver\\PdoDriver');
    $databaseManager->registerDriver('sqlite', 'ride\\library\\database\\driver\\PdoDriver');
    $databaseManager->registerDriver('postgres', 'ride\\library\\database\\driver\\PostgresPdoDriver');
    
    $databaseManager->registerConnection('my-database', new Dsn('mysql://user:pass@host/database'));
    $databaseManager->registerConnection('my-2nd-database', new Dsn('sqlite:///path/to/file'));
    
    return $databaseManager;
}

function getConnection(DatabaseManager $databaseManager) {
    $connections = $databaseManager->getConnections();
    
    // get the default connection
    $connection = $databaseManager->getConnection();
    
    if ($databaseManager->hasConnection('my-database')) {
        // get my connection
        $connection = $databaseManager->getConnection('my-database');
        
        // get my connection but don't connect just yet
        $connection = $databaseManager->getConnection('my-database', false);
    }
    
    if (!$connection->isConnected()) {
        $connection->connect();
    }
    
    return $connection;
}

function executeSelectSql(Driver $connection) {
    $sql = 
        'SELECT ' . $connection->quoteIdentifier('id') . ' ' . 
        'FROM ' . $connection->quoteIdentifier('MyTable') . ' ' .
        'WHERE ' . $connection->quoteIdentifier('name') . ' LIKE ' . $connection->quoteValue('%Ride%');
        
    $result = $connection->execute($sql);
    
    // get the columns or the column count
    $columns = $result->getColumns();
    $columnCount = $result->getColumnCount();

    // same for rows
    $rows = $result->getRows(); 
    $rowCount = $result->getRowCount();

    // get the first or the last row
    $firstRow = $result->getFirst();
    $lastRow = $result->getLast();

    // you can loop the result straight
    foreach ($result as $row) {
        echo $row['id'];
    }
}
    
function executeInsertSql(Driver $connection) {
    try {
        $connection->beginTransaction();
        
        $sql = 
            'INSERT INTO ' . $connection->quoteIdentifier('MyTable') . ' ' .
            'VALUES (' . $connection->quoteValue('My name') . ')';
            
        $result = $connection->execute($sql);
        
        if ($connection->isTransactionStarted()) {
            $connection->commitTransaction();
        } 
    
        $connection->getLastInsertId();
    } catch (DatabaseException $exception) {
        $connection->rollback();
        
        throw $exception;
    }
}

function executeStatement(Driver $connection) {
    $statement = new SelectStatement(new FieldExpression('id'));
    $statement->addField($field);
    $statement->addTable(new TableExpression('MyTable'));
    $statement->addCondition(new SimpleCondition(new FieldExpression('name'), new ScalarExpression('%Ride%'), '='));
    
    $result = $connection->executeStatement($statement);
}
```

## Related Modules

- [ride/app-database](https://github.com/all-ride/ride-app-database)
- [ride/app-orm](https://github.com/all-ride/ride-app-orm)
- [ride/cli-databases](https://github.com/all-ride/ride-cli-database)
- [ride/lib-log](https://github.com/all-ride/ride-lib-log)
- [ride/lib-orm](https://github.com/all-ride/ride-lib-orm)
- [ride/lib-validation](https://github.com/all-ride/ride-lib-validation)

## Installation

You can use [Composer](http://getcomposer.org) to install this library.

```
composer require ride/lib-database
```

## Convert utf8 to utf8mb4

Show character set and collation variables:

```sql
SHOW VARIABLES WHERE Variable_name LIKE 'character\_set\_%' OR Variable_name LIKE 'collation%';
```
Set character set and collate on database:
```sql
ALTER DATABASE db CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
```

Set character set and collate on table:
```sql 
ALTER TABLE table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
Generate ALTER TABLE queries to set character set and collate:
```sql 
SELECT CONCAT('ALTER TABLE ', TABLE_SCHEMA, '.', TABLE_NAME, ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;')
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'db';
```

Converting the data in the columns is not required, since utf8mb4 is backwards compatible with utf8.
