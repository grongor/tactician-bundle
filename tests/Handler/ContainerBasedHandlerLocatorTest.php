<?php

namespace League\Tactician\Bundle\Tests\Handler;

use League\Tactician\Bundle\Handler\ContainerBasedHandlerLocator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerBasedHandlerLocatorTest extends TestCase
{
    public function testGetHandler()
    {
        $container = new ContainerBuilder();
        $container->register('fake_command_handler', 'stdClass')->setPublic(true);
        $container->compile();

        $locator = new ContainerBasedHandlerLocator($container, [
            'FakeCommand' => 'fake_command_handler',
            'OtherCommand' => 'other_command_handler'
        ]);

        $this->assertInstanceOf('stdClass', $locator->getHandlerForCommand('FakeCommand'));
    }

    /**
     * @expectedException \League\Tactician\Exception\MissingHandlerException
     */
    public function testGetHandlerThrowsExceptionForNotFound()
    {
        $locator = new ContainerBasedHandlerLocator(new ContainerBuilder(), [
            'OtherCommand' => 'my_bundle.order.id'
        ]);

        $locator->getHandlerForCommand('MyFakeCommand');
    }

}
