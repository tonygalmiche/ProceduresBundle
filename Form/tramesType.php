<?php

namespace OVE\ProceduresBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class tramesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

      $config_ckeditor=array(
        'config' => array(
          'toolbar' => array(
            array('name' => 'basicstyles', 'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat')),
            array('name' => 'clipboard'  , 'items' => array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo')),

            /*array('name' => 'basicstyles', 'items' => array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' )),*/
            array('name' => 'paragraph'  , 'items' => array('NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock')),
            array('name' => 'links'      , 'items' => array('Link','Unlink' )),

            array('name' => 'insert'     , 'items' => array('Table','HorizontalRule','SpecialChar' )),
            /*array('name' => 'insert'     , 'items' => array('Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' )),*/
            '/',
            array('name' => 'styles'     , 'items' => array('Styles','Format','Font','FontSize' )),
            array('name' => 'colors'     , 'items' => array('TextColor','BGColor' )),
            /*array('name' => 'tools'      , 'items' => array('Maximize', 'ShowBlocks','-','About' )),*/
            array('name' => 'tools'      , 'items' => array('Maximize' )),
          ),
          'uiColor' => '#ffffff',
        )
      );




        $builder
            ->add('nom')
            ->add('contenu', 'ckeditor',  $config_ckeditor)
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OVE\ProceduresBundle\Entity\trames'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ove_proceduresbundle_trames';
    }
}
