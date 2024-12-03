<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NameParserService;
use Illuminate\Support\Facades\Log;

class ParseHomeowners extends Command
{
    protected $signature = 'parse:homeowners {file}';
    protected $description = 'Parse homeowners data from CSV file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error('File not found.');
            return;
        }

        if (($handle = fopen($filePath, 'r')) !== false) {
            // Skip the header row
            $header = fgetcsv($handle);

            $rowNumber = 1;

            while (($data = fgetcsv($handle)) !== false) {
                try {
                    $name = $data[0];
                    $parsedData = NameParserService::parse($name);

                    foreach ($parsedData as $person) {
                        $this->info(json_encode($person));
                    }

                } catch (\InvalidArgumentException $e) {
                    $this->error("Error parsing row $rowNumber: " . $e->getMessage());
                }

                $rowNumber++;
            }

            fclose($handle);
        } else {
            $this->error('Unable to open the file.');
        }
    }
}
