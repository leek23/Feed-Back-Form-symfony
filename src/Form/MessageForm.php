<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user',UserType::class, array('constraints' => new \Symfony\Component\Validator\Constraints\Valid()))
            ->add('text', TextareaType::class)
            ->add('captcha', IntegerType::class, array(
                'label' => $options['attr']['n1'].' + '.$options['attr']['n2'],
                "mapped" => false,
            ))
            ->add('save', SubmitType::class, ['label' => 'Create Post'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class
        ]);
    }
}