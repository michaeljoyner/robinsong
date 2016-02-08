<?php

namespace App\Console\Commands;

use App\Content\ContentRepository;
use App\Content\ContentSnapshotFactory;
use App\Content\ContentWriter;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Parser;

class MapContentStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edible:map';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Maps the content structure defined in your edible.yml file to your database';

    /**
     * Create a new command instance.
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
        $writer = $this->makeWriter();
        $this->showChanges($writer->additions(), $writer->deletions());
        if($this->confirm('Are you sure you want to continue? [y|N]')) {
            $writer->setContentStructure();
            $this->info('All done.');
        } else {
            $this->warn('aborted');
        }

    }

    protected function makeWriter()
    {
        $fileSnapshot = (new ContentSnapshotFactory(new Parser()))->makeSnapshotFromYmlFile(base_path('edible.yml'));
        $databaseSnapshot = (new ContentSnapshotFactory(new Parser()))->makeSnapshotFromRepo(new ContentRepository());
        return new ContentWriter($fileSnapshot, $databaseSnapshot);
    }

    protected function showChanges($additions, $deletions)
    {
        $headers = ['PAGES', 'TEXTBLOCKS', 'GALLERIES'];
        if(! empty($additions)) {
            $this->info('You are about to make these additions:');
            $this->table($headers, $additions, 'symfony-style-guide');
        }
        if(! empty($deletions)) {
            $this->warn('You are about to commit to these deletions');
            $this->table($headers, $deletions, 'symfony-style-guide');
        }
    }
}
