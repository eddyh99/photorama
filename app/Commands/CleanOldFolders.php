<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use FilesystemIterator;

class CleanOldFolders extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'clean:oldfolders';
    protected $description = 'Menghapus folder yang lebih dari 7 hari.';

    public function run(array $params)
    {
        $directory = FCPATH . 'assets/photobooth/';
        $days      = 7;

        $this->deleteOldFolders($directory, $days);
    }

    protected function deleteOldFolders($directory, $days)
    {
        if (!is_dir($directory)) {
            CLI::write("Directory $directory tidak ditemukan.", 'red');
            return;
        }

        $folders = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);
        $deletedCount = 0;

        foreach ($folders as $folder) {
            if ($folder->isDir()) {
                $lastModified = $folder->getMTime();
                $diff = time() - $lastModified;

                if ($diff > ($days * 86400)) {
                    $this->deleteFolder($folder->getRealPath());
                    CLI::write("Folder $folder telah dihapus.", 'green');
                    $deletedCount++;
                }
            }
        }

        // Jika tidak ada folder yang dihapus
        if ($deletedCount === 0) {
            CLI::write("Tidak ada folder yang perlu dihapus.", 'yellow');
        } else {
            CLI::write("Total folder yang dihapus: $deletedCount.", 'green');
        }
    }

    protected function deleteFolder($path)
    {
        if (!is_dir($path)) {
            return;
        }

        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->deleteFolder($file) : unlink($file);
        }

        rmdir($path);
    }
}
