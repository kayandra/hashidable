<?php

namespace Kayandra\Hashidable\Tests;

use Kayandra\Hashidable\Encoder;
use Kayandra\Hashidable\Tests\Models\Model;
use Illuminate\Support\Facades\Route;

class HashidableTest extends TestCase
{
	private $encoder;

	protected function setUp(): void
	{
		parent::setUp();

		$this->encoder = new Encoder(hash('sha512', Model::class));

		Route::middleware('bindings')->get('/hashidable/{model}', [
			'as' => 'model',
			'uses' => fn (Model $model) => $model,
		]);
	}

	/** @test */
	public function can_auto_hook_hash_ids()
	{
		$model = factory(Model::class)->create();

		$this->assertEquals(
			$this->encoder->encode($model->getKey()),
			$model->hashid
		);
	}

	/** @test */
	public function ensure_hash_ids_are_auto_resolved_in_route_bindings()
	{
		$model = factory(Model::class)->create();
		$response = $this->get($url = route('model', $model));

		$this->assertEquals(
			sprintf("%s/hashidable/%s", config('app.url'), $model->hashid),
			$url
		);

		$this->assertEquals($model->toArray(), $response->json());
	}

	/** @test */
	public function it_returns_the_hashid_of_a_model_as_its_route_key()
	{
		$model = factory(Model::class)->create();

		$this->assertEquals(
			$this->encoder->encode($model->id),
			$model->getRouteKey()
		);
	}
}
