<?php

namespace MicroweberPackages\Admin\MenuBuilder;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class Menu {

    public ItemInterface $menuItems;
    public FactoryInterface $menuFactory;

    public function __construct($name = 'default')
    {
        $this->menuFactory = new \Knp\Menu\MenuFactory();
        $this->menuItems = $this->menuFactory->createItem($name);
    }

    public function addChild($title, $options = [])
    {
        $this->menuItems->addChild($title, $options);
        return $this;
    }

    public function getChild($title)
    {
        return $this->menuItems->getChild($title);
    }

    public function render()
    {
        $renderer = new \Knp\Menu\Renderer\ListRenderer(new \Knp\Menu\Matcher\Matcher());
        return $renderer->render($this->menuItems);
    }

}
