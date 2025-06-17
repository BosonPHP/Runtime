<?php

declare(strict_types=1);

namespace Boson\Bridge\Symfony\DependencyInjection;

use Boson\Window\WindowCreateInfo;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @phpstan-type BosonConfigType array{
 *     name: non-empty-string,
 *     schemes: non-empty-list<non-empty-string>,
 *     debug: bool,
 *     entrypoint: non-empty-string,
 *     width: int<1, max>,
 *     height: int<1, max>,
 *     ...
 * }
 */
final class BosonConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tree = new TreeBuilder('boson');

        $root = $tree->getRootNode();

        /** @phpstan-ignore-next-line : Known non-fixable issue */
        $root->children()
            ->stringNode('name')
                ->info('The name of the boson application')
                ->cannotBeEmpty()
                ->defaultValue('boson')
            ->end()
            ->arrayNode('schemes')
                ->info('An URI schemes of the application')
                ->stringPrototype()
                    ->cannotBeEmpty()
                ->end()
                ->cannotBeEmpty()
                ->defaultValue(['boson'])
            ->end()
            ->booleanNode('debug')
                ->info('Enable or disable application debug mode')
                ->defaultValue('%kernel.debug%')
            ->end()
            ->scalarNode('entrypoint')
                ->info('The entrypoint URI of the application')
                ->cannotBeEmpty()
                ->defaultValue('boson://localhost')
            ->end()
            ->integerNode('width')
                ->info('Initial width of the default application window')
                ->defaultValue(WindowCreateInfo::DEFAULT_WIDTH)
            ->end()
            ->integerNode('height')
                ->info('Initial height of the default application window')
                ->defaultValue(WindowCreateInfo::DEFAULT_HEIGHT)
            ->end()
        ->end();

        return $tree;
    }
}
