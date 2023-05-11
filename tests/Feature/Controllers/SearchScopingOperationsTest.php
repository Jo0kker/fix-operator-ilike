<?php

namespace Lomkit\Rest\Tests\Feature\Controllers;

use Illuminate\Support\Facades\Gate;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Tests\Feature\TestCase;
use Lomkit\Rest\Tests\Support\Database\Factories\BelongsToManyRelationFactory;
use Lomkit\Rest\Tests\Support\Database\Factories\BelongsToRelationFactory;
use Lomkit\Rest\Tests\Support\Database\Factories\HasManyRelationFactory;
use Lomkit\Rest\Tests\Support\Database\Factories\HasOneRelationFactory;
use Lomkit\Rest\Tests\Support\Database\Factories\ModelFactory;
use Lomkit\Rest\Tests\Support\Models\BelongsToRelation;
use Lomkit\Rest\Tests\Support\Models\Model;
use Lomkit\Rest\Tests\Support\Policies\GreenPolicy;
use Lomkit\Rest\Tests\Support\Rest\Resources\BelongsToManyResource;
use Lomkit\Rest\Tests\Support\Rest\Resources\BelongsToResource;
use Lomkit\Rest\Tests\Support\Rest\Resources\HasManyResource;
use Lomkit\Rest\Tests\Support\Rest\Resources\HasOneResource;
use Lomkit\Rest\Tests\Support\Rest\Resources\ModelResource;

class SearchScopingOperationsTest extends TestCase
{
    /** @test */
    public function getting_a_list_of_resources_scoping_unauthorized_relation(): void
    {
        ModelFactory::new()->count(2)->create();

        Gate::policy(Model::class, GreenPolicy::class);

        $response = $this->post(
            '/api/models/search',
            [
                'scopes' => [
                    ['name' => 'not_authorized_scope'],
                ],
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors' => ['scopes.0.name']]);
    }

    /** @test */
    public function getting_a_list_of_resources_scoping_numbered(): void
    {
        $matchingModel = ModelFactory::new()->create(['number' => 1])->fresh();
        $matchingModel2 = ModelFactory::new()->create(['number' => -1])->fresh();

        Gate::policy(Model::class, GreenPolicy::class);

        $response = $this->post(
            '/api/models/search',
            [
                'scopes' => [
                    ['name' => 'numbered'],
                ],
            ],
            ['Accept' => 'application/json']
        );

        $this->assertResourcePaginated(
            $response,
            [$matchingModel],
            new ModelResource,
        );
    }

    /** @test */
    public function getting_a_list_of_resources_scoping_numbered_with_params(): void
    {
        $matchingModel = ModelFactory::new()->create(['number' => 1])->fresh();
        $matchingModel2 = ModelFactory::new()->create(['number' => 2])->fresh();

        Gate::policy(Model::class, GreenPolicy::class);

        $response = $this->post(
            '/api/models/search',
            [
                'scopes' => [
                    ['name' => 'numbered', 'parameters' => [1]],
                ],
            ],
            ['Accept' => 'application/json']
        );

        $this->assertResourcePaginated(
            $response,
            [$matchingModel2],
            new ModelResource,
        );
    }
}