<?php
namespace Tests\Feature;

use BazaarVoice\Elements\ProductElement;
use BazaarVoice\Order\Feed as OrderFeed;
use BazaarVoice\Product\Feed as ProductFeed;
use PHPUnit\Framework\TestCase;

class FeedTest extends TestCase
{
    /** @test */
    public function it_generates_an_interaction_feed()
    {
        // Set
        $feed = new OrderFeed();
        $element = $feed->newFeed('InteractionFeed');
        $product = new ProductElement(12345678, 'Product Name', 'ProductCategoryId123', 'http://localhost/12345678', 'http://localhost/12345678/image');
        $product2 = new ProductElement(12345679, 'Product Name 2', 'ProductCategoryId123', 'http://localhost/12345679', 'http://localhost/12345679/image');
        $order = $feed->newOrder('22/03/1987', 'john@doe.com', 'John Doe', 'userId123', 'pt_BR', [$product, $product2]);
        $element->addInteraction($order);
        $element->addInteraction($order);
        $expectedFeed = file_get_contents('tests/fixtures/interaction-feed.xml');

        // Actions
        $result = $feed->printFeed($element);

        $result = $this->prepareResultForTesting($result);
        $expectedFeed = $this->prepareResultForTesting($expectedFeed);

        // Assertions
        $this->assertSame($expectedFeed, $result);
    }

    /** @test */
    public function it_generates_a_product_feed()
    {
        // Set
        $feed = new ProductFeed();
        $element = $feed->newFeed('ProductFeed');
        $product = new ProductElement(12345678, 'Product Name', 'ProductCategoryId123', 'http://localhost/12345678', 'http://localhost/12345678/image');
        $product2 = new ProductElement(12345679, 'Product Name 2', 'ProductCategoryId123', 'http://localhost/12345679', 'http://localhost/12345679/image');
        $element->addProduct($product);
        $element->addProduct($product2);
        $expectedFeed = file_get_contents('tests/fixtures/product-feed.xml');

        // Actions
        $result = $feed->printFeed($element);

        $result = $this->prepareResultForTesting($result);
        $expectedFeed = $this->prepareResultForTesting($expectedFeed);

        // Assertions
        $this->assertSame($expectedFeed, $result);
    }

    /** @test */
    public function it_generates_a_brand_feed()
    {
        // Set
        $feed = new ProductFeed();
        $element = $feed->newFeed('BrandFeed');
        $firstBrand = $feed->newBrand('first-brand', 'First Brand');
        $secondBrand = $feed->newBrand('second-brand', 'Second Brand');
        $element->addBrand($firstBrand);
        $element->addBrand($secondBrand);
        $expectedFeed = file_get_contents('tests/fixtures/brand-feed.xml');

        // Actions
        $result = $feed->printFeed($element);

        $result = $this->prepareResultForTesting($result);
        $expectedFeed = $this->prepareResultForTesting($expectedFeed);

        // Assertions
        $this->assertSame($expectedFeed, $result);
    }

    /** @test */
    public function it_generates_a_category_feed()
    {
        // Set
        $feed = new ProductFeed();
        $feedElement = $feed->newFeed('CategoryFeed');

        $firstCategory = $feed->newCategory('first-category', 'First Category', 'http://localhost/first-category');
        $secondCategory = $feed->newCategory('second-category', 'Second Category', 'http://localhost/second-category');
        $feedElement->addCategory($firstCategory);
        $feedElement->addCategory($secondCategory);
        $expectedFeed = file_get_contents('tests/fixtures/category-feed.xml');

        // Actions
        $result = $feed->printFeed($feedElement);

        $result = $this->prepareResultForTesting($result);
        $expectedFeed = $this->prepareResultForTesting($expectedFeed);

        // Assertions
        $this->assertSame($expectedFeed, $result);
    }

    public function prepareResultForTesting(string $result): string
    {
        $firstPos = strpos($result, 'extractDate="');

        return substr_replace($result, 'extractDate="', $firstPos, 32);
    }
}