<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Services\PostService;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Fluent;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    private PostService $post_service;

    public function setUp(): void
    {
        $this->hotfixSqlite();
        parent::setUp();
        $this->user = User::factory()->create();
        $this->post_service = new PostService();
    }

    public function hotfixSqlite()
    {
        \Illuminate\Database\Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection
            {
                public function getSchemaBuilder()
                {
                    if ($this->schemaGrammar === null) {
                        $this->useDefaultSchemaGrammar();
                    }
                    return new class($this) extends SQLiteBuilder
                    {
                        protected function createBlueprint($table, Closure $callback = null)
                        {
                            return new class($table, $callback) extends Blueprint
                            {
                                public function dropForeign($index)
                                {
                                    return new Fluent();
                                }
                            };
                        }
                    };
                }
            };
        });
    }

    public function testIndex()
    {
        $post = $this->post_service->store([
            'user_id' => $this->user->id,
            'message' => "New Post",
            'is_public' => true
        ]);

        $post = $this->post_service->store([
            'user_id' => $this->user->id,
            'message' => "New Post 1",
            'is_public' => true
        ]);

        $response = $this->actingAs($this->user)->get('/api/v1/post');
        $response->assertOk();

        $response->assertSee('posts');
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('posts', 2)
        );
    }

    public function testStore()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($this->user)->post('/api/v1/post', [
            'message' => 'Test message',
            'is_public' => true,
            'photo' => $file
        ]);

        $response->assertCreated();
    }

    public function testShow()
    {
        $post = $this->post_service->store([
            'user_id' => $this->user->id,
            'message' => "New Post",
            'is_public' => true
        ]);

        $response = $this->actingAs($this->user)->get("/api/v1/post/{$post->id}");
        $response->assertOk();
        $response->assertSee('post');
    }

    public function testUpdate()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');

        $post = $this->post_service->store([
            'user_id' => $this->user->id,
            'message' => "New Post",
            'is_public' => true
        ]);

        $response = $this->actingAs($this->user)->put("/api/v1/post/{$post->id}", [
            'message' => 'Test message',
            'is_public' => true,
            'photo' => $file
        ]);

        $response->assertOk();
    }

    public function testDestroy()
    {
        $post = $this->post_service->store([
            'user_id' => $this->user->id,
            'message' => "New Post",
            'is_public' => true
        ]);

        $response = $this->actingAs($this->user)->delete("/api/v1/post/{$post->id}");
        $response->assertOk();
    }
}
