<?php
declare(strict_types=1);

namespace Ergnuor\TableDataGateway;

use Ergnuor\Criteria\ConfigBuilder\ConfigBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Serializer;

interface RegistryInterface
{
    public function getSerializer(): Serializer;
    public function getConfigBuilder(): ConfigBuilder;

    public function getExpressionMapperContainer(): ContainerInterface;
}