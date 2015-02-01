<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');
        $menu->setChildrenAttribute('id', 'side-menu');

        $menu->addChild('Dashboard', array('route' => 'dashboard'))
            ->setAttribute('class', 'nav-header');

        $menu->addChild('Dashboard', array('route' => 'dashboard'))
            ->setAttribute('icon', 'fa fa-th-large');

        $menu->addChild('Issues', array('route' => 'issues'))
            ->setAttribute('icon', 'fa fa-table');
        $menu->addChild('Timeline', array('route' => 'timeline'))
            ->setAttribute('icon', 'fa fa-table');
        $menu->addChild('Pin Board', array('route' => 'pins'))
            ->setAttribute('icon', 'fa fa-table');

        $menu->addChild('Settings', array('route' => 'settings'))
            ->setAttribute('icon', 'fa fa-table')
            ->setChildrenAttribute('class', 'nav nav-second-level')
        ;
        $menu['Settings']->addChild('Organizations', array('route' => 'settings'))
            ->setAttribute('icon', 'fa fa-table');
        $menu['Settings']->addChild('Users', array('route' => 'settings'))
            ->setAttribute('icon', 'fa fa-table');


        return $menu;
    }
}