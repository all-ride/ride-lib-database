<?php

namespace pallo\library\database\driver;

use pallo\core\Zibo;

use pallo\library\filesystem\File;

/**
 * Extension for a database driver to export the database
 */
interface ExportableDriver {

    /**
     * Exports the database to the provided file
     * @param pallo\core\Zibo $pallo Instance of Zibo
     * @param pallo\library\filesystem\File $file File for the export
     * @return null
     */
    public function export(Zibo $pallo, File $file);

}