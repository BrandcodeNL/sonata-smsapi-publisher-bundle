<?php
/*
 * This file is part of the BrandcodeNL SonataSmsapiPublisherBundle.
 * (c) BrandcodeNL
 */
namespace BrandcodeNL\SonataSmsapiPublisherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 *  @author Jeroen de Kok <jeroen.dekok@aveq.nl>
 * This is the class that validates and merges configuration from your app/config files. *
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('brandcode_nl_sonata_smsapi_publisher');
            
        
        $rootNode
            ->children()                
                ->scalarNode('api_key')
                    ->isRequired()
                ->end()                
            ->end();

        return $treeBuilder;
    }
}
