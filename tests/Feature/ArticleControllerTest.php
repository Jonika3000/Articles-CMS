<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
    }

    /** @test */
    public function it_can_get_all_articles()
    {
        $response = $this->getJson('api/article');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_can_show_an_article()
    {
        $article = Article::factory()->create();

        $response = $this->getJson("api/article/show/{$article->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['id' => $article->id]);
    }

    /** @test */
    public function it_returns_404_when_article_not_found()
    {
        $response = $this->getJson('api/article/show/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Article not found']);
    }

    /** @test */
    public function it_can_store_an_article()
    {
        $response = $this->actingAs($this->user, 'api')
            ->postJson('api/article', [
                'title' => 'Test Title',
                'body' => 'Test Body',
            ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson(['title' => 'Test Title']);
    }

    /** @test */
    public function it_validates_article_store_request()
    {
        $response = $this->actingAs($this->user, 'api')
            ->postJson('api/article', [
                'title' => '',
                'body' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['title', 'body']);
    }

    /** @test */
    public function it_can_update_an_article()
    {
        $article = Article::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')
            ->putJson("api/article/{$article->id}", [
                'title' => 'Updated Title',
                'body' => 'Updated Body',
            ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['title' => 'Updated Title']);
    }

    /** @test */
    public function it_returns_403_when_updating_article_not_owned_by_user()
    {
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user, 'api')
            ->putJson("api/article/{$article->id}", [
                'title' => 'New Title',
                'body' => 'New Body',
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function it_can_delete_an_article()
    {
        $article = Article::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')
            ->deleteJson("api/article/{$article->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Article deleted successfully']);
    }

    /** @test */
    public function it_returns_403_when_deleting_article_not_owned_by_user()
    {
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user, 'api')
            ->deleteJson("api/article/{$article->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_article()
    {
        $response = $this->actingAs($this->user, 'api')
            ->deleteJson('api/article/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Article not found']);
    }
}
