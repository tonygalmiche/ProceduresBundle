<?php

namespace OVE\ProceduresBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class proceduresType extends AbstractType
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
            array('name' => 'links'      , 'items' => array('Link','Unlink','Anchor' )),


            array('name' => 'insert'     , 'items' => array('Table','HorizontalRule','SpecialChar' )),
            /*array('name' => 'insert'     , 'items' => array('Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' )),*/
            '/',
            array('name' => 'styles'     , 'items' => array('Styles','Format','Font','FontSize' )),
            array('name' => 'colors'     , 'items' => array('TextColor','BGColor' )),
            /*array('name' => 'tools'      , 'items' => array('Maximize', 'ShowBlocks','-','About' )),*/
            array('name' => 'tools'      , 'items' => array('Source','Maximize' )),

          ),
          'uiColor' => '#ffffff',
          /*'contentsCss' => 'https://procedures-demo.fondation-ove.fr/bundles/oveprocedures/css/procedures.css',*/

        ),
      );

          //http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar

/*


  config.uiColor = '#AADC6E';
    config.contentsCss = '/path/ckeditor/mysitestyles.css';


        'styles' => array(
            'my_styles' => array(
                array('name' => 'Blue Title'  , 'element' => 'h2', 'styles' => array('color' => 'Blue')),
                array('name' => 'CSS Style'   , 'element' => 'span', 'attributes' => array('class' => 'my_style')),
                array('name' => 'Widget Style', 'type' => 'widget', 'widget' => 'my_widget', 'attributes' => array('class' => 'my_widget_style'))
            ),
        ),




$builder->add('field', 'ckeditor', array(
    'config' => array(
        'stylesSet' => 'my_styles',
    ),
    'styles' => array(
        'my_styles' => array(
            array('name' => 'Blue Title', 'element' => 'h2', 'styles' => array('color' => 'Blue')),
            array('name' => 'CSS Style', 'element' => 'span', 'attributes' => array('class' => 'my_style')),
            array('name' => 'Widget Style', 'type' => 'widget' => 'widget' => 'my_widget', 'attributes' => array('class' => 'my_widget_style')),
        ),
    ),
));
*/


/*
config.toolbar_Full =
[
	{ name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
	{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
	{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 
        'HiddenField' ] },
	'/',
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
	'/',
	{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] },
	{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
];
 */




//                'validation'  => "Validation",


        $builder
            ->add('type', 'choice', array('choices' => array(
                'procedure'     => "Procédure",
                'regle_gestion' => "Règle de gestion",
            )))
            ->add('etat', 'choice', array('choices' => array(
                'redaction'    => "Rédaction",
                'verification' => "Vérification",
                'approbation'  => "Approbation",
                'valide'       => "Validé",
                'archive'      => "Archivé",
            )))
            ->add('domaine')
            ->add('domaineid')
            ->add('nom')
            ->add('fiche')
            ->add('version')
            ->add('objet', 'ckeditor',  $config_ckeditor)
            ->add('terminologie', 'ckeditor',  $config_ckeditor)
            ->add('indice')
            /*->add('date_redaction')
            ->add('date_verifie')
            ->add('date_approuve')

            ->add('date_mise_a_jour')
            ->add('nom_redaction')
            ->add('nom_verifie')
            ->add('nom_approuve')
            ->add('nom_application')*/

            //->add('date_application')

            ->add('date_application','date',array(
              'required' => false,
              'widget' => 'single_text',
              'format' => 'dd/MM/yyyy',
              'attr' => array('class' => 'date')
            ))


            ->add('diagramme')
            ->add('description', 'ckeditor',  $config_ckeditor)
            ->add('intervenants')
            ->add('redacteurs')
            ->add('redacteursid')
            ->add('verificateurs')
            ->add('verificateursid')
            ->add('approbateurs')
            ->add('approbateursid')
            ->add('lecteurs')
            ->add('lecteursid')
            ->add('mots_cles')
            ->add('mots_clesid')
            ->add('objet_modification')
            ->add('pieces_jointes')
            ->add('liens_html')
        ;



/*
$builder->add('myDate','date',array(
'widget' => 'single_text',
'format' => 'dd-MM-yyyy',
'attr' => array('class' => 'date')
))
*/

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OVE\ProceduresBundle\Entity\procedures'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ove_proceduresbundle_procedures';
    }
}
