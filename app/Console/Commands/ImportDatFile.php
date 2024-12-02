<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportDatFile extends Command
{
    protected $signature = 'import:datfile {file}';
    protected $description = 'Import data from a .dat file into the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get the file path from the command argument
        $filePath = $this->argument('file');
        
        // Construct the full path to the file
        $fullPath = storage_path('app/public/' . $filePath);

        // Check if file exists
        if (!file_exists($fullPath)) {
            $this->error('File does not exist.');
            return;
        }

        // Read the file
        $fileContents = file_get_contents($fullPath);
        
        // Split the file contents into lines
        $lines = explode("\n", $fileContents);
        
        // Process each line (assuming CSV format for this example)
        foreach ($lines as $line) {
            // Skip empty lines
            if (empty(trim($line))) {
                continue;
            }

            // Split the line by delimiter (assuming tab-separated values)
            $data = preg_split('/\s+/', trim($line));

            // Check if the line has the expected number of columns (in this case, 6)
            if (count($data) < 6) {
                $this->error('Malformed line: ' . $line);
                continue;
            }

            // Add debugging information
            $this->info('Inserting: ' . implode(', ', $data));

            // Insert the data into the database
            DB::table('data')->insert([
                'staff_id' => $data[0],
                'date_time' => $data[1],
                'col_1' => $data[2],
                'col_2' => $data[3],
                'col_3' => $data[4],
                'col_4' => $data[5],
            ]);
        }

        $this->info('Data imported successfully.');
    }
}
