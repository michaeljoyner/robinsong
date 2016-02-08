<?php

use App\Content\Page;
use Illuminate\Database\Seeder;

class EdiblesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $home = Page::create(['name' => 'home', 'description' => 'The homepage']);
        $about = Page::create(['name' => 'about', 'description' => 'The about page']);
        $intro = $home->addTextblock('intro', 'the homepage intro', 0, 'Welcome to my website');
        $slider = $home->addGallery('slider', 'banner images');
        $about->addTextblock('intro', 'the about intro', 1, 'This is a story all about me');
    }
}
