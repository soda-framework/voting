<?php

namespace Soda\Voting\Console;

use Illuminate\Console\Command;
use Soda\Voting\Components\ReportSeeder;

class Reports extends Command
{
    protected $signature = 'soda:reports:voting';
    protected $description = 'Seed Voting Reports for the Soda Database';

    /**
     * Runs seeds for Soda CMS, defaulting to 'SodaSeeder'.
     */
    public function handle()
    {
        $this->call('db:seed', [
            '--class' => ReportSeeder::class,
        ]);
    }
}
