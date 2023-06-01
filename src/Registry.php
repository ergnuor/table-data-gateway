<?php
declare(strict_types=1);

namespace Ergnuor\TableDataGateway;

use Ergnuor\Criteria\ConfigBuilder\ConfigBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Serializer;

class Registry implements RegistryInterface
{
    private ConfigBuilder $configBuilder;
    private ContainerInterface $expressionMapperContainer;
    private Serializer $DTOSerializer;

    public function __construct(
        Serializer $DTOSerializer,
        ConfigBuilder $configBuilder,
        ContainerInterface $expressionMapperContainer
    ) {
        $this->DTOSerializer = $DTOSerializer;
        $this->configBuilder = $configBuilder;
        $this->expressionMapperContainer = $expressionMapperContainer;
    }

    public function getSerializer(): Serializer
    {
        return $this->DTOSerializer;
    }

    public function getConfigBuilder(): ConfigBuilder
    {
        return $this->configBuilder;
    }

    public function getExpressionMapperContainer(): ContainerInterface
    {
        return $this->expressionMapperContainer;
    }
}