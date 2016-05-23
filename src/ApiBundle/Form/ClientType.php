<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'mapped' => 'name',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'max' => 255
                    ])
                ]
            ])
            ->add('allowedOrigins', CollectionType::class, [
                'allow_add'=>true,
                'entry_type'=>UrlType::class,
                'constraints'=>new All([
                    'constraints'=>[
                        new Url()
                    ]
                ]),
            ])
            ->add('redirectUris', CollectionType::class, [
                'mapped' => 'redirectUris',
                'allow_add'=>true,
                'entry_type'=>UrlType::class,
                'constraints'=>new All([
                    'constraints'=>[
                        new Url()
                    ]
                ]),
            ])
            ->add('allowedGrantTypes', ChoiceType::class, [
                'multiple' => true,
                'choices'  => [
                    'authorization_code' => 'authorization_code',
                    'password' => 'password',
                    'refresh_token' => 'refresh_token',
                    'token' => 'token',
                    'client_credentials' => 'client_credentials',
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ApiBundle\Entity\Client',
            'csrf_protection' => false,
        ]);
    }
}
