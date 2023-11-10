<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Adresse Email'
            ])
            // ->add('password', RepeatedType::class, [
            //     'type' => PasswordType::class,
            //     'first_options' => ['label' => 'Mot de passe', 'attr' => ['class' => 'form-control']],
            //     'second_options' => ['label' => 'Répétez le mot de passe', 'attr' => ['class' => 'form-control']],
            // ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles'
            ]);

            if (!$options['is_edit']) {
                $builder->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Mot de passe', 'attr' => ['class' => 'form-control']],
                    'second_options' => ['label' => 'Répétez le mot de passe', 'attr' => ['class' => 'form-control']],
                ]);
            };
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);
    }
}
