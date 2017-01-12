<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Artisan;
use App\User;
use App\Audio;

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
     * Audio storage disk
     *
     * @var FilesystemAdapter
     */
    protected $disk;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->disk = Storage::disk('audio');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user  = User::findOrFail($this->argument('id'));
        $files = $user->audio()->orderBy('created_at', 'asc')->get();
        $path  = $this->disk->getDriver()->getAdapter()->getPathPrefix();
        $ffprobe = env('FFPROBE_PATH', '/usr/bin/ffprobe');

        if ($files->count() <= 1) { return; }
        
        $index  = $path . str_random(10) . '.txt';
        $result = $path . str_random(10) . '.wav';

        // generate txt index of audio filenames to pass to ffmpeg concat
        file_put_contents(
            $index,
            implode("\n", array_map(function ($file) use ($path) {
                return 'file ' . $path . $file->filename;
            }, $files->all()))
        );

        try {
            // concatenate the audio files
            $concat = new Process(env('FFMPEG_PATH', '/usr/bin/ffmpeg') . " -f concat -safe 0 -i $index -c copy $result");
            $concat->mustRun();
            
            
            // cleanup old audio files, db records, and tmp index file
            $user->audio()->delete();
            $this->disk->delete(basename($index));
            foreach ($files as $file) {
                $this->disk->delete($file->filename);
            }

            // get duration of resulting file
            Artisan::call('getAudioDuration', ['path' => $result]);
            $duration = Artisan::output();

            // assign new db record
            $audio = new Audio();
            $audio->filename = basename($result);
            $audio->duration = $duration;
            $user->audio()->save($audio);

        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
        }
    }
}
