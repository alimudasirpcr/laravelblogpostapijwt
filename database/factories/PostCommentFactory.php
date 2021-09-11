<?php

namespace Database\Factories;

use App\Models\PostComment;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

            return [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'comment' => $this->faker->sentence,
                'approved' =>1,
                'post_id' => BlogPost::factory(),
            ];

    }
}
