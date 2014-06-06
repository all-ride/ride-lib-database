<?php

namespace ride\library\database\driver;

use ride\core\Zibo;

use ride\library\system\file\File;
use ride\library\system\System;

/**
 * Extension for a database driver to export the database
 */
interface ExportableDriver {

    /**
     * Exports the database to the provided file
     * @param \ride\library\system\System $ride Instance of Zibo
     * @param \ride\library\system\file\File $file File for the export
     * @return null
     */
    public function export(System $ride, File $file);

}