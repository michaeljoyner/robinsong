<?php
use App\Blog\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/31/16
 * Time: 10:42 AM
 */
class BlogControllerTest extends TestCase
{

    use DatabaseMigrations, AsLoggedInUser, TestsImageUploads;

    /**
     *@test
     */
    public function a_blog_post_can_be_created()
    {
        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/blog/posts', [
            'title' => 'My Test Post'
        ]);
        $this->assertEquals(302, $response->status());

        $this->seeInDatabase('posts', [
            'title' => 'My Test Post',
            'slug' => 'my-test-post'
        ]);
    }

    /**
     *@test
     */
    public function a_posts_title_description_and_content_can_be_edited()
    {
        $post = factory(Post::class)->create();

        $this->visit('/admin/blog/posts/'.$post->id.'/edit')
            ->type('An edited title', 'title')
            ->type('A brand new description', 'description')
            ->type('Content that is dropping shit like its hot, with pure newness', 'content')
            ->press('Save Changes');

        $this->seeInDatabase('posts', [
            'id' => $post->id,
            'title' => 'An edited title',
            'description' => 'A brand new description',
            'content' => 'Content that is dropping shit like its hot, with pure newness'
        ]);

    }

    /**
     *@test
     */
    public function images_can_be_uploaded_to_a_blog_post()
    {
        $post = factory(Post::class)->create();

        $this->withoutMiddleware();

        $response = $this->call('POST', '/admin/blog/posts/'.$post->id.'/images/uploads', [], [], [
            'file' => $this->prepareFileUpload('tests/testpic1.png')
        ]);
        $this->assertEquals(200, $response->status());
        $this->assertContains('"location":', $response->getContent());
        $this->assertCount(1, $post->getMedia());

        $post->clearMediaCollection();
    }

    /**
     * @test
     */
    public function a_blog_posts_published_status_can_be_toggled()
    {
        $post = factory(Post::class)->create();
//        $this->assertFalse($post->published);

        $this->withoutMiddleware();
        $response = $this->call('POST', '/admin/blog/posts/'.$post->id.'/publish', [
            'publish' => true
        ]);
        $this->assertEquals(200, $response->status());
        $this->assertContains('"new_state":true', $response->getContent());

        $this->seeInDatabase('posts', [
            'id' => $post->id,
            'published' => 1
        ]);
    }
}