<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Exception;

class R0D0 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'r0d0';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database and file storage from backup.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Starting restore operation...");

        try {
            // Make sure app is up to avoid maintenance mode issues
            Artisan::call('up');

            // Drop all existing tables
            $this->warn("Dropping all database tables...");
            Schema::disableForeignKeyConstraints();

            $tables = DB::select('SHOW TABLES');
            $dbName = 'Tables_in_' . DB::getDatabaseName();

            foreach ($tables as $table) {
                $tableName = $table->$dbName;
                DB::statement("DROP TABLE IF EXISTS `$tableName`");
            }

            Schema::enableForeignKeyConstraints();

            // Restore DB from backup
            $backupPath = storage_path('app/backup.sql');
            if (!File::exists($backupPath)) {
                $this->error("Backup file not found at: $backupPath");
                return Command::FAILURE;
            }

            $this->info("Restoring database from backup...");
            DB::unprepared(File::get($backupPath));

            // Restore uploaded files
            $this->info("Restoring uploaded files...");
            $source = storage_path('app/uploads');
            $destination = storage_path('app/public/uploads');

            if (File::exists($source)) {
                File::copyDirectory($source, $destination);
            } else {
                $this->warn("No uploads found at: $source");
            }

            $this->info("Restore completed at: " . now());
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error("Restore failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
