<?php

namespace ride\library\database\driver;

use ride\core\Zibo;

use ride\library\filesystem\File;

/**
 * Extension for a database driver to export the database
 */
interface ExportableDriver {

    /**
     * Exports the database to the provided file
     * @param ride\core\Zibo $ride Instance of Zibo
     * @param ride\library\filesystem\File $file File for the export
     * @return null
     */
    public function export(Zibo $ride, File $file);

}