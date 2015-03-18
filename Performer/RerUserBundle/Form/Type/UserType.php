<?php

namespace Performer\RerUserBundle\Form\Type;

use Performer\RerUserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isNew = true;
        if (isset($options['data'])) {
            if (!is_null($options['data']->getId())) {
                $isNew = false;
            }
            $user = $options['data'];
            $roles = $user::getRoleLabels();
        } else {
            $roles = User::getRoleLabels();
        }

        $builder
            ->add('username', null, ['label' => 'Nome utente'])
            ->add('domain', null, ['label' => 'Dominio'])
            ->add('roles', 'choice', ['choices' => $roles, 'multiple' => true, 'label' => 'Ruoli'])
            ->add('active', 'checkbox', ['required' => false, 'label' => 'Attivo'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Performer\RerUserBundle\Entity\User',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'utente';
    }
}
