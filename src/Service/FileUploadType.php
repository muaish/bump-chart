<?php
// src/Form/FileUploadType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FileUploadType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('upload_file', FileType::class, [
        'label' => false,
        'mapped' => false,
        'required' => true,
        'data_class' => null,
        'attr' => ['accept' => '.xlsx'],
        'constraints' => [
          new File([
            'mimeTypes' => [
              'application/vnd.ms-excel',
              'application/excel',
              'application/vnd.msexcel',
              'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ],
            'mimeTypesMessage' => "This document isn't valid.",
          ])
        ],
      ])
      ->add('send', SubmitType::class);
  }
}
