<?php

namespace App\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class AtributoAdmin extends AbstractAdmin
{
    public function getTemplate($name)
    {
        switch ($name) {
            case 'list':
                return 'AppAdminBundle:CRUD:modal.html.twig';
                break;

            default:
                return parent::getTemplate($name);
                break;
        }
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('status')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('createdBy')
            ->add('updatedBy')
            ->add('status', null, ['label' => 'Publicado'])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => ['template' => 'AppAdminBundle:CRUD:list__action_delete.html.twig'],
                ),
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('valor', 'sonata_type_collection',
                [
                    'label' => 'Valores',
                    'required' => false,
                    'by_reference' => true,
                    'btn_add' => 'Nuevo valor'
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table'
                ]
            )
            ->add('status', null, ['label' => 'Publicado'])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('status')
        ;
    }

    public function prePersist($object)
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setCreatedBy($user);
        $object->setUpdatedBy($user);


        if($object->getValor()){
            foreach($object->getValor() as $valor)
            {
                $valor->setAtributo($object);
            }
        }
    }

    public function preUpdate($object)
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setUpdatedBy($user);

        if($object->getValor()){
            foreach($object->getValor() as $valor)
            {
                $valor->setAtributo($object);
            }
        }
    }
}
