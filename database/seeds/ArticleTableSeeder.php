<?php

use Illuminate\Database\Seeder;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('article')->delete();
        
        \DB::table('article')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Title',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'photo' => 'https://www.ajactraining.org/wp-content/uploads/2019/09/image-placeholder.jpg',
                'created_at' => '2020-05-22 00:01:17',
                'updated_at' => '2020-05-22 00:09:32'
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'Title 1',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'photo' => 'https://www.ajactraining.org/wp-content/uploads/2019/09/image-placeholder.jpg',
                'created_at' => '2020-05-22 00:01:17',
                'updated_at' => '2020-05-22 00:09:32'
            ),
            
        ));
    }
}
