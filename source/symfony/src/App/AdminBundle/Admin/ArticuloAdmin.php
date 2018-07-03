<?php

namespace App\AdminBundle\Admin;

use App\CoreBundle\Entity\Articulo;
use App\CoreBundle\Entity\ArticuloAtributoValor;
use App\CoreBundle\Entity\Atributo;
use App\CoreBundle\Entity\ValorAtributo;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ArticuloAdmin extends AbstractAdmin
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
            ->add('price')
            ->add('description')
            ->add('stock_minimo')
            ->add('stock')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('status');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('producto')
            ->add('name', null, ['editable' => true])
            ->add('price', null, ['editable' => true])
            ->add('description', 'html', ['editable' => true])
            ->add('valor_atributo', null, ['label' => 'Atributos'])
            ->add('stock_minimo', null, ['editable' => true])
            ->add('stock', null, ['editable' => true])
            ->add('totalSold', null, ['editable' => true,'label'=> 'Total vendidos'])
            ->add('createdAt')
            ->add('updatedAt')
            ->add('status', null, ['label' => 'Publicado', 'editable' => true])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => ['template' => 'AppAdminBundle:CRUD:list__action_delete.html.twig'],
                ],
            ]);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Basic Data')
                ->with('')
                    ->add('name')
                    ->add('price')
                    ->add('description')
                    ->add('valor_atributo')
                    ->add('stock_minimo')
                    ->add('slug')
                    ->add('stock')
                    ->add('status', null, ['label' => 'Publicado'])
                ->end()
            ->end();
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('stock_minimo')
            ->add('stock')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('status');
    }

    public function prePersist($object)
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setCreatedBy($user);
        $object->setUpdatedBy($user);
    }

    public function preUpdate($object)
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setUpdatedBy($user);

        $object->setSlug(null);
    }
}
