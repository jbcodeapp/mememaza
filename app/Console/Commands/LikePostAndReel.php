<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Post;
use App\Models\Reel;
use App\Models\Story;
use App\Models\Like;

class LikePostAndReel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:like';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anonymous Users Like Post And Reel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modals = ['Post', 'Reel', 'Story'];
        foreach($modals as $modal) {
            $modelType = "App\Models\\".$modal;
		    $mod = new $modelType;
            // $Items = $mod::where('created_at', '>', now()->subMinutes(15))->get();
            $Items = $mod::inRandomOrder()->limit(10)->get();
            foreach ($Items as $item) {
                // Like::create([
                //     'type' => $modal,
                //     'type_id' => $item->id,
                //     'user_id' => rand(1,20),
                // ]);

                Like::updateOrCreate([
                    'type' => $modal,
                    'type_id' => $item->id,
                    'user_id' => rand(1,20),
                ]);
                
            }
        }        
        
        $this->info('Anonymous Liked new posts and reels successfully!');
        return Command::SUCCESS;
    }
    

}
