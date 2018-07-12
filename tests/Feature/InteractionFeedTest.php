<?php
namespace BazaarVoice;

use BazaarVoice\Elements\ProductElement;
use BazaarVoice\Order\Feed;
use PHPUnit\Framework\TestCase;

class InteractionFeedTest extends TestCase
{
    /** @test */
    public function it_generates_an_interaction_feed()
    {
        // Set
        $feed = new Feed();
        $element = $feed->newFeed('InteractionFeed');
        $product = new ProductElement(12345678, 'Product Name', 'ProductCategoryId123', 'http://localhost/12345678', 'http://localhost/12345678/image');
        $product2 = new ProductElement(12345679, 'Product Name 2', 'ProductCategoryId123', 'http://localhost/12345679', 'http://localhost/12345679/image');
        $order = $feed->newOrder('22/03/1987', 'john@doe.com', 'John Doe', 'userId123', 'pt_BR', [$product, $product2]);
        $element->addInteraction($order);
        $element->addInteraction($order);
        $expectedFeed = file_get_contents('tests/fixtures/interaction-feed.xml');

        // Actions
        $result = $feed->printFeed($element);

        // Work aroud nice
        $result = $this->prepareResultForTesting($result);
        $expectedFeed = $this->prepareResultForTesting($expectedFeed);

        // Assertions
        $this->assertEquals($expectedFeed, $result);
    }

    public function prepareResultForTesting(string $result): string
    {
        $firstPos = strpos($result, 'extractDate="');

        return substr_replace($result, 'extractDate="', $firstPos, 32);
    }
}
