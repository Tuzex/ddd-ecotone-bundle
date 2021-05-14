<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Ddd\Test\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Ddd\DependencyInjection\DddEcotoneExtension;
use Tuzex\Bundle\Ddd\Messaging\EcotoneAwareCommandBus;
use Tuzex\Bundle\Ddd\Messaging\EcotoneAwareDomainEventBus;
use Tuzex\Bundle\Ddd\Messaging\Interceptor\CommandsAspectDispatcher;
use Tuzex\Bundle\Ddd\Messaging\Interceptor\DomainEventsAspectDispatcher;
use Tuzex\Ddd\Application\CommandBus;
use Tuzex\Ddd\Application\DomainEventBus;
use Tuzex\Ddd\Application\Service\CommandsSpooler;
use Tuzex\Ddd\Application\Service\DomainEventsEmitter;
use Tuzex\Ddd\Infrastructure\Messaging\InMemoryCommandsSpooler;
use Tuzex\Ddd\Infrastructure\Messaging\InMemoryDomainEventsEmitter;

final class DddEcotoneExtensionTest extends TestCase
{
    private DddEcotoneExtension $dddExtension;
    private ContainerBuilder $containerBuilder;

    protected function setUp(): void
    {
        $this->dddExtension = new DddEcotoneExtension();
        $this->containerBuilder = new ContainerBuilder();

        parent::setUp();
    }

    public function testItConfigsNamespaces(): void
    {
        $this->dddExtension->prepend($this->containerBuilder);

        $ecotoneExtension = $this->containerBuilder->getExtensionConfig('ecotone');
        $ecotoneConfigs = $ecotoneExtension[0];

        $this->assertArrayHasKey('namespaces', $ecotoneConfigs);
        $this->assertNotEmpty($ecotoneConfigs['namespaces']);
    }

    /**
     * @dataProvider provideServiceIds
     */
    public function testItRegistersExpectedServices(string $serviceId): void
    {
        $this->dddExtension->load([], $this->containerBuilder);

        $this->assertTrue($this->containerBuilder->hasDefinition($serviceId));
    }

    public function provideServiceIds(): iterable
    {
        $services = [
            'command-bus' => EcotoneAwareCommandBus::class,
            'domain-event-bus' => EcotoneAwareDomainEventBus::class,
            'commands-spooler' => InMemoryCommandsSpooler::class,
            'domain-events-emitter' => InMemoryDomainEventsEmitter::class,
            'commands-aspect-dispatcher' => CommandsAspectDispatcher::class,
            'domain-events-aspect-dispatcher' => DomainEventsAspectDispatcher::class,
        ];

        foreach ($services as $id => $service) {
            yield $id => [
                'serviceId' => $service,
            ];
        }
    }

    /**
     * @dataProvider provideServiceAliases
     */
    public function testItRegistersAliases(string $serviceAlias, string $serviceId): void
    {
        $this->dddExtension->load([], $this->containerBuilder);

        $this->assertSame($serviceId, (string) $this->containerBuilder->getAlias($serviceAlias));
    }

    public function provideServiceAliases(): iterable
    {
        $serviceAliases = [
            CommandBus::class => EcotoneAwareCommandBus::class,
            CommandsSpooler::class => InMemoryCommandsSpooler::class,
            DomainEventBus::class => EcotoneAwareDomainEventBus::class,
            DomainEventsEmitter::class => InMemoryDomainEventsEmitter::class,
        ];

        foreach ($serviceAliases as $serviceAlias => $serviceId) {
            yield $serviceAlias => [
                'serviceAlias' => $serviceAlias,
                'serviceId' => $serviceId,
            ];
        }
    }
}
