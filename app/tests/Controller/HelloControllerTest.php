<?php
/**
 * Hello controller tests.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HelloControllerTest.
 */
class HelloControllerTest extends WebTestCase
{
    /**
     * Test '/hello' route.
     */
    public function testHelloRoute(): void
    {
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/hello');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultHttpStatusCode);
    }

    /**
     * Test 'Hello World!' message.
     */
    public function testHelloWorldMessage(): void
    {
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/hello');
        $result = $client->getResponse()->getContent();

        // then
        $this->assertStringContainsString('Hello World!', $result);
    }

    /**
     * Test 'Hello World!' header.
     */
    public function testHelloWorldHeaderTag(): void
    {
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/hello');

        // then
        $this->assertSelectorTextContains('html title', 'Hello World!');
        $this->assertSelectorTextContains('html p', 'Hello World!');
    }

    /**
     * Test 'Hello World!' personalized.
     *
     * @param string $name              Name
     * @param string $expectedGreetings Expected greeting
     *
     * @dataProvider dataProviderForTestPersonalizedGreetings
     */
    public function testHelloWorldPersonalized(string $name, string $expectedGreetings): void
    {
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/hello/'.$name);

        // then
        $this->assertSelectorTextContains('html title', $expectedGreetings);
        $this->assertSelectorTextContains('html p', $expectedGreetings);
    }

    /**
     * Data provider for testHelloWorldPersonalized() method.
     *
     * @return \Generator Test case
     */
    public function dataProviderForTestPersonalizedGreetings(): \Generator
    {
        yield 'Hello Ann' => [
            'name' => 'Ann',
            'expectedGreetings' => 'Hello Ann!',
        ];
        yield 'Hello John' => [
            'name' => 'John',
            'expectedGreetings' => 'Hello John!',
        ];
        yield 'Hello Beth' => [
            'name' => 'Beth',
            'expectedGreetings' => 'Hello Beth!',
        ];
    }

    /**
     * Test 'Hello World!' personalized.
     *
     * @param string $nameNorm          Name
     * @param string $expectedNormGreet Expected normalized greeting
     *
     * @dataProvider dataProviderForTestPersonalizedNormalizedGreetings
     */
    public function testHelloWorldPersonalizedNormalized(string $nameNorm, string $expectedNormGreet): void
    {
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/hello/'.$nameNorm);

        // then
        $this->assertSelectorTextContains('html title', $expectedNormGreet);
        $this->assertSelectorTextContains('html p', $expectedNormGreet);
    }

    public function dataProviderForTestPersonalizedNormalizedGreetings(): \Generator
    {
        yield 'Hello Ann' => [
            'nameNorm' => 'ann',
            'expectedNormGreet' => 'Hello Ann!',
        ];
        yield 'Hello John' => [
            'nameNorm' => 'john',
            'expectedNormGreet' => 'Hello John!',
        ];
        yield 'Hello Beth' => [
            'nameNorm' => 'beth',
            'expectedNormGreet' => 'Hello Beth!',
        ];
    }
}
