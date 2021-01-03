<?php

namespace Kayandra\Hashidable\Tests;

use Illuminate\Support\Str;
use Kayandra\Hashidable\Encoder;
use Illuminate\Support\Facades\Route;
use Kayandra\Hashidable\Tests\Models\Model;
use Kayandra\Hashidable\Tests\Models\ModelConfig;

class HashidableTest extends TestCase
{
	private Encoder $encoder;

	protected function setUp(): void
	{
		parent::setUp();

		$this->encoder = new Encoder(Model::class, config('hashidable'));

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

	/**
	 * @test
	 * @dataProvider hashLengthDataProvider
	 */
	public function change_hash_length($length)
	{
		$model = factory(Model::class)->create();

		config(['hashidable.length' => $length]);

		$this->assertEquals($length, mb_strlen($model->hashid));
	}

	public function hashLengthDataProvider(): array {
		return array_map(fn () => [mt_rand(8, 36)], array_fill(0, 10, 1));
	}

	/** @test */
	public function can_prefix_hash()
	{
		$model = factory(Model::class)->create();

		config(['hashidable.prefix' => 'edit']);

		$this->assertTrue(Str::startsWith($model->hashid, 'edit'));
	}

	/** @test */
	public function prefixes_does_not_affect_decoding()
	{
		config(['hashidable.prefix' => 'edit']);

		$model = factory(Model::class)->create();
		$encoder = new Encoder(Model::class, config('hashidable'));

		$this->assertEquals(1, $encoder->decode($model->hashid));
	}

	/** @test */
	public function can_suffix_hash()
	{
		$model = factory(Model::class)->create();

		config(['hashidable.suffix' => 'end']);

		$this->assertTrue(Str::endsWith($model->hashid, 'end'));
	}

	/** @test */
	public function suffixes_does_not_affect_decoding()
	{
		config(['hashidable.suffix' => 'end']);

		$model = factory(Model::class)->create();
		$encoder = new Encoder(Model::class, config('hashidable'));

		$this->assertEquals(1, $encoder->decode($model->hashid));
	}

	/** @test */
	public function can_change_hash_separator()
	{
		$model = factory(Model::class)->create();

		config(['hashidable.prefix' => 'edit']);
		config(['hashidable.suffix' => 'end']);
		config(['hashidable.separator' => '_']);

		$this->assertTrue(Str::startsWith($model->hashid, 'edit_'));
		$this->assertTrue(Str::endsWith($model->hashid, '_end'));
	}

	/** @test */
	public function configuration_per_model_instance()
	{
		$model = factory(ModelConfig::class)->create();

		$this->assertEquals(64, mb_strlen($model->hashid));
	}

	/** @test */
	public function model_config_superceeds_global_config()
	{
		$model = factory(ModelConfig::class)->create();

		config(['hashidable.length' => 128]);

		$this->assertEquals(64, mb_strlen($model->hashid));
	}

	/** @test */
	public function can_change_character_set()
	{
		$model = factory(Model::class)->create();

		$this->assertFalse(ctype_xdigit($model->hashid));

		config(['hashidable.charset' => 'ABCDEF1234567890']);

		$this->assertTrue(ctype_xdigit($model->hashid));
	}
}
