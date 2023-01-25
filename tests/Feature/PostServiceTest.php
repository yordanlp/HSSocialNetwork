<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Database\Factories\PostFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    private PostService $postService;
    private Post $post;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->postService = new PostService();
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
            "message" => fake()->sentence(12),
            "is_public" => true
        ]);
    }

    public function testGetPosts()
    {
        // Create a user and a post

        // Test getting all posts
        $posts = $this->postService->getPosts();
        $this->assertCount(1, $posts);
        $this->assertEquals($this->post->id, $posts[0]->id);

        // Test getting posts for a specific user
        $posts = $this->postService->getPosts($this->user);
        $this->assertCount(1, $posts);
        $this->assertEquals($this->post->id, $posts[0]->id);

        // Test searching for posts
        $posts = $this->postService->getPosts(null, $this->post->message);
        $this->assertCount(1, $posts);
        $this->assertEquals($this->post->id, $posts[0]->id);

        // Test paginating posts
        $posts = $this->postService->getPosts(null, "", 1);
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $posts);
        $this->assertCount(1, $posts);
        $this->assertEquals($this->post->id, $posts[0]->id);
    }

    public function testStore()
    {
        File::copy(base_path("tests/test_image.jpg"), base_path('tests/1.jpg'));
        // Test storing a post without a photo
        $data = [
            'user_id' => $this->user->id,
            'message' => 'Test post',
            'is_public' => true
        ];
        $post = $this->postService->store($data);
        $this->assertEquals('Test post', $post->message);

        // Test storing a post with a photo
        $path_to_photo = base_path('tests/1.jpg');
        $post = $this->postService->store($data, $path_to_photo);
        $this->assertEquals('Test post', $post->message);
        $this->assertCount(1, $post->load('media')->media);
    }

    public function testGetPost()
    {

        // Test getting the post
        $result = $this->postService->getPost($this->post->id);
        $this->assertEquals($this->post->id, $result->id);

        // Test getting the post with relations
        $result = $this->postService->getPost($this->post->id, ['user']);
        $this->assertEquals($this->post->id, $result->id);
        $this->assertNotNull($result->user);
    }

    public function testUpdate()
    {

        // Test updating the post without a photo
        $data = ['message' => 'Updated test post'];
        $result = $this->postService->update($this->post->id, $data);
        $this->assertEquals('Updated test post', $result->message);

        // Test updating the post with a photo
        File::copy(base_path("tests/test_image.jpg"), base_path('tests/2.jpg'));
        $path_to_photo = base_path('tests/2.jpg');
        $data['message'] = 'Updated post with photo';
        $result = $this->postService->update($this->post->id, $data, $path_to_photo);
        $this->assertEquals('Updated post with photo', $result->message);
        $this->assertCount(1, $result->load('media')->media);
    }

    public function testDestroy()
    {
        // Create a post
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            "message" => fake()->sentence(12),
            "is_public" => true
        ]);

        // Test deleting the post
        $result = $this->postService->destroy($post->id);
        $this->assertEquals($post->id, $result->id);

        // Test that the post no longer exists
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->postService->getPost($post->id);
    }
}
