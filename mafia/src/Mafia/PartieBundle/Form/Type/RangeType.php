<?php

namespace Mafia\PartieBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * @Service("acme_demo.form.type.range")
 * @Tag("form.type", attributes = {"alias" = "range"})
 */
class RangeType extends AbstractType
{
    /**
     * Get parent
     *
     * @return string
     */
    public function getParent()
    {
        return 'number';
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'range';
    }


}