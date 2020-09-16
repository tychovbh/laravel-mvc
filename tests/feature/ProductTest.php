<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Product;
use Tychovbh\Mvc\Http\Resources\ProductResource;
use Tychovbh\Tests\Mvc\TestCase;

class ProductTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $products = factory(Product::class, 2)->create();
        $this->index('products.index', ProductResource::collection($products));
    }

    /**
     * @test
     */
    public function itCanShow()
    {
        factory(Product::class, 2)->create();
        $product = factory(Product::class)->create();

        $this->show('products.show', (new ProductResource($product)));
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        $product = factory(Product::class)->make();
        $this->store('products.store', (new ProductResource($product)), $product->toArray());
    }

    /**
     * @test
     */
    public function itCanUpdate()
    {
        $product = factory(Product::class)->create();
        $this->update('products.update', (new ProductResource($product)), $product->toArray());
    }

    /**
     * @test
     */
    public function itCanDelete()
    {
        $product = factory(Product::class)->create();
        $this->destroy('products.destroy', (new ProductResource($product)));
    }
}

