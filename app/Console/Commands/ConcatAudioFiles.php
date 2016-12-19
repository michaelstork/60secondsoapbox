<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class ConcatAudioFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'concatAudioFiles {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Concatenate .wav files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        $path = base_path('storage/app/audio/'.$id);
        $ffmpeg = env('FFMPEG_PATH', '/usr/bin/ffmpeg');

        // create text file listing audio files to be concatenated
        $init = new Process(">$path/files.txt; for f in $path/*.wav; do echo \"file \$f\" >> $path/files.txt; done");
        $init->mustRun();

        // concatenate the audio
        $concat = new Process("$ffmpeg -f concat -i $path/files.txt -c copy $path/tmp.wav");
        $concat->mustRun();
        
        // mv result and delete tmp files
        $cleanup = new Process("mv $path/tmp.wav $path/audio.wav && find $path -type f ! -name 'audio.wav' -delete");
        $cleanup->mustRun();

    }
}
