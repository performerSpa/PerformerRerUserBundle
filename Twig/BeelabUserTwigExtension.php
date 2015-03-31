<?php

/*
 * This class is copied and adapted from BeelabUserBundle
 * https://github.com/Bee-Lab/BeelabUserBundle
 */

namespace Performer\RerUserBundle\Twig;

use Twig_Extension;

/**
 * This extension is used to register some global variables
 */
class BeelabUserTwigExtension extends Twig_Extension
{
    protected $layout;
    protected $route;

    /**
     * @param string $layout layout name (for "extends" statement)
     * @param string $route  route used in index.html.twig
     */
    public function __construct($layout, $route)
    {
        $this->layout = $layout;
        $this->route = $route;
    }

    /**
     * {@inheritDoc}
     */
    public function getGlobals()
    {
        return [
            'performer_rer_user_layout' => $this->layout,
            'performer_rer_user_route'  => $this->route,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'performer_rer_user_twig_extension';
    }
}
