<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetAudioDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getAudioDuration {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output duration of audio file in milliseconds';

    /**
     * FFPROBE path
     *
     * @var string
     */
    protected $ffprobe;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->ffprobe = env('FFPROBE_PATH', '/usr/bin/ffprobe');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $seconds = `{$this->ffprobe} -v quiet -print_format compact=print_section=0:nokey=1:escape=csv -show_entries format=duration {$path}`;
        $this->info($seconds * 1000);
    }
}
