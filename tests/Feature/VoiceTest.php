<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Question;
use App\Models\User;
use App\Models\Voice;


class VoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_voice_post_is_not_public(){
        $response = $this->postJson('api/voices',
            ['question_id' => 1, 'value' => 1]);

        $response->assertStatus(401);
    }

    public function test_can_post_on_someone_elses_question(){
        $question_user = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $question_user->id]);

        $voice_user = User::factory()->create();

        $response = $this->actingAs($voice_user, 'sanctum')->postJson('api/voices',
            ['question_id' => $question->id, 'value' => 1]);

        $response->assertStatus(200);

        $this->assertDatabaseHas(Voice::class,
            ['user_id' => $voice_user->id, 'question_id' => $question->id, 'value' => 1]);
    }

    public function test_cannot_post_on_nonexisting_question(){
        $question_user = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $question_user->id]);

        $voice_user = User::factory()->create();
        $response = $this->actingAs($voice_user, 'sanctum')->postJson('api/voices',
            ['question_id' => $question->id + 1, 'value' => '1']);

        $response->assertStatus(422);
    }

    public function test_can_update_your_own_voice(){
        $question_user = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $question_user->id]);

        $voice_user = User::factory()->create();
        $this->actingAs($voice_user, 'sanctum')->postJson('api/voices',
            ['question_id' => $question->id, 'value' => '1']);

        $response = $this->actingAs($voice_user, 'sanctum')->postJson('api/voices',
            ['question_id' => $question->id, 'value' => '0']);

        $response->assertStatus(201);
    }

    public function test_cannot_post_same_voice_twice(){
        $question_user = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $question_user->id]);

        $voice_user = User::factory()->create();
        $this->actingAs($voice_user, 'sanctum')->postJson('api/voices',
            ['question_id' => $question->id, 'value' => '1']);

        $response = $this->actingAs($voice_user, 'sanctum')->postJson('api/voices',
            ['question_id' => $question->id, 'value' => '1', 'asdasd' => 'basdbb']);

        $response->assertStatus(500);
    }
}
