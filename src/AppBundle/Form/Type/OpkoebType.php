<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Place;
use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\EasyAdminAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 */
class OpkoebType extends AbstractType {

  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {

    $builder->add('matrik1');
    $builder->add('matrik2', null, array('label' => 'My New Label:'));

//    $placeholder = [];
//    foreach (['year', 'month', 'day', 'hour', 'minute'] as $key) {
//      $placeholder[$key] = 'form.type.occurrence.datetime.placeholder.' . $key;
//    }
//
//    $builder
//      ->add('startDate', DateTimeType::class, [
//        'placeholder' => $placeholder,
//        'required' => TRUE,
//      ])
//      ->add('endDate', DateTimeType::class, [
//        'placeholder' => $placeholder,
//        'required' => TRUE,
//      ])
//      ->add('place', EasyAdminAutocompleteType::class, [
//        'class' => Place::class,
//        'required' => TRUE,
//      ]);
//
//    $builder->add('ticketPriceRange', PriceRangeType::class, [
//      'required' => TRUE,
//    ]);
//
//    $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
//      $form = $event->getForm();
//      $place = $form->get('place')->getData();
//      if (!$place) {
//        $form->get('place')->addError(new FormError('Please select a place'));
//      }
//      $startDate = $form->get('startDate')->getData();
//      $endDate = $form->get('endDate')->getData();
//      if ($startDate && $endDate && $endDate <= $startDate) {
//        $form->get('endDate')->addError(new FormError('End date must be after start date'));
//      }
//    });
  }

  /**
   * @param OptionsResolverInterface $resolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => 'AppBundle\Entity\Opkoeb'
    ]);
  }

  /**
   * @return string
   */
  public function getName() {
    return 'appbundle_opkoeb';
  }

}
