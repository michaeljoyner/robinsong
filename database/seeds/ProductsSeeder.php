<?php

use App\Stock\Collection;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collection = factory(Collection::class)->create(['name' => 'Weddings']);

        $babies = factory(Collection::class)->create(['name' => 'Babies']);

        $babyBooks = $babies->addCategory([
            'name'        => 'Birthing Books',
            'description' => 'So you remember all the bloody details'
        ]);

        $babProd1 = $babyBooks->addProduct([
            'name' => 'It is Crowning',
            'description' => 'A humourous tale of a crowning baby',
            'price' => 12,
            'weight' => 24,
            'available' => 1
        ]);

        $babProd2 = $babyBooks->addProduct([
            'name' => 'My First Newborn',
            'description' => 'A tragic comedy',
            'price' => 12,
            'weight' => 24,
            'available' => 1
        ]);

        $frames = $babies->addCategory([
            'name'        => 'Picture Frames',
            'description' => 'Put your baby in a box'
        ]);

        $frame1 = $frames->addProduct([
            'name' => 'Standard Wrought Iron Frame',
            'description' => 'Pure Greyjoy',
            'price' => 16,
            'weight' => 54,
            'available' => 1
        ]);

        $frame2 = $frames->addProduct([
            'name' => 'Gold Leaf Sparkly Frame',
            'description' => 'Because your baby is straight up Indian',
            'price' => 88,
            'weight' => 74,
            'available' => 1
        ]);

        $cat1 = $collection->addCategory([
            'name'        => 'Guest Books',
            'description' => 'Fine, hand crafted books to store all those wonderful memories'
        ]);
        $cat2 = $collection->addCategory([
            'name'        => 'Decor',
            'description' => 'Fine, hand crafted decor to enhance your wonderful day'
        ]);

        $prod1 = $cat1->addProduct([
            'name' => 'Lace-lined Ivory Tome',
            'description' => 'A book of true beauty and class, this is bound to leave your guests speechless and aghast',
            'price' => 21,
            'weight' => 36,
            'available' => 1
        ]);

        $prod2 = $cat1->addProduct([
            'name' => 'Fun in the Sun Book',
            'description' => 'A lighthearted book with spiders and surfers having a raucous wedding.',
            'price' => 31,
            'weight' => 26,
            'available' => 1
        ]);
        $prod3 = $cat2->addProduct([
            'name' => 'Classy Candlesticks',
            'description' => 'The silver shines so, so bright, you will never forget your wedding night',
            'price' => 89,
            'weight' => 460,
            'available' => 1
        ]);

        $prod4 = $cat2->addProduct([
            'name' => 'Naughty Napkins',
            'description' => 'Joke napkins for those who like fun without thought. They are not actually naughty, just very sad.',
            'price' => 7,
            'weight' => 12,
            'available' => 1
        ]);

        $opt1 = $prod1->addOption('Ribbon Colour');
        $opt1->addValue('Gold');
        $opt1->addValue('Silver');
        $opt1->addValue('Purple');
        $opt1->addValue('Yellow');

        $opt2 = $prod1->addOption('Paper Type');
        $opt2->addValue('Blotting');
        $opt2->addValue('Tracing');
        $opt2->addValue('Poster board');

        $prod1->addCustomisation('Couple Names');
        $prod1->addCustomisation('Book Intro', true);

        $opt2 = $prod2->addOption('Ribbon Colour');
        $opt2->addValue('Gold');
        $opt2->addValue('Silver');
        $opt2->addValue('Purple');
        $opt2->addValue('Yellow');

        $opt3 = $prod2->addOption('Paper Type');
        $opt3->addValue('Blotting');
        $opt3->addValue('Tracing');
        $opt3->addValue('Poster board');

        $prod2->addCustomisation('Couple Names');
        $prod2->addCustomisation('Book Intro', true);


    }
}
