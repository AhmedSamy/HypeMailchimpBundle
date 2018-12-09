<?php

namespace Hype\MailchimpBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface {


    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder('hype_mailchimp');
        $rootNode = \method_exists(TreeBuilder::class, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('hype_mailchimp');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode->children()
                    ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('default_list')->isRequired()->cannotBeEmpty()->end()
                    ->booleanNode('ssl')->defaultTrue()->end()
                    ->integerNode('timeout')->defaultValue(20)->end()
                  ->end();

        return $treeBuilder;
    }

}
