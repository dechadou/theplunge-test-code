<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\AdminBundle\Controller;

use App\CoreBundle\Entity\Orders\Estado;
use App\CoreBundle\Entity\Orders\Nota;
use Doctrine\Common\Inflector\Inflector;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Util\AdminObjectAclData;
use Sonata\AdminBundle\Util\AdminObjectAclManipulator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;


class CustomCRUDController extends CRUDController
{

    public function sendEmails($object, $estado)
    {
        $userData = null;
        $userEmail = false;
        $emailService = $this->container->get('email_service');
        foreach ($object->getUserData() as $d) {
            $userData = $d;
            $userEmail = $d->getEmail();
        }

        // send email to user if trigger is enabled
        if ($userEmail) {
            $completado = $this->getDoctrine()->getRepository(Estado::class)->findOneBy(
                [
                    'name' => $estado
                ]
            );
            $trigger = $emailService->checkTriggers($object->getTienda(), $object->getEnvio(), $completado);
            if ($trigger) {
                $emailService->sendEmail($trigger->getSubject(), 'tienda@abrecultura.com', $userEmail, $trigger->getTemplate(), $userData, $object);
            }
        }
    }

    public function markCompletedAction()
    {
        $object = $this->admin->getSubject();
        $id = $object->getId();

        $this->sendEmails($object, 'Completado');

        // change status of order.
        $estado = $this->getDoctrine()->getRepository(Estado::class)->findOneBy(
            [
                'name' => 'Completado'
            ]
        );
        $object->setEstado($estado);

        foreach ($object->getUserData() as $d) {
            $userData = $d;
            $userEmail = $d->getEmail();
        }

        $this->saveNota($estado, $userEmail, $object, 'Boton Completado: Estado cambiado a: ' . $estado);

        $this->getDoctrine()->getManager()->persist($object);
        $this->getDoctrine()->getManager()->flush();


        $referer = $request = $this->getRequest()->headers->get('referer');

        $this->addFlash('sonata_flash_success', 'Estado actualizados con Exito para la orden #' . $object->getWoocomerceOrderId());
        return $this->redirect($referer);
    }

    /**
     * Batch action.
     *
     * @return Response|RedirectResponse
     *
     * @throws \ReflectionException
     */
    public function batchAction()
    {
        $request = $this->getRequest();
        $restMethod = $this->getRestMethod();

        $confirmation = $request->get('confirmation', false);

        if ($data = json_decode($request->get('data'), true)) {
            $action = $data['action'];
            $idx = $data['idx'];
            $allElements = $data['all_elements'];
            $request->request->replace(array_merge($request->request->all(), $data));
        } else {
            $request->request->set('idx', $request->get('idx', []));
            $request->request->set('all_elements', $request->get('all_elements', false));

            $action = $request->get('action');
            $idx = $request->get('idx');
            $allElements = $request->get('all_elements');
            $data = $request->request->all();

            unset($data['_sonata_csrf_token']);
        }

        // NEXT_MAJOR: Remove reflection check.
        $reflector = new \ReflectionMethod($this->admin, 'getBatchActions');
        if ($reflector->getDeclaringClass()->getName() === get_class($this->admin)) {
            @trigger_error('Override Sonata\AdminBundle\Admin\AbstractAdmin::getBatchActions method'
                . ' is deprecated since version 3.2.'
                . ' Use Sonata\AdminBundle\Admin\AbstractAdmin::configureBatchActions instead.'
                . ' The method will be final in 4.0.', E_USER_DEPRECATED
            );
        }
        $batchActions = $this->admin->getBatchActions();
        if (!array_key_exists($action, $batchActions)) {
            throw new \RuntimeException(sprintf('The `%s` batch action is not defined', $action));
        }

        $camelizedAction = Inflector::classify($action);
        $isRelevantAction = sprintf('batchAction%sIsRelevant', $camelizedAction);

        if (method_exists($this, $isRelevantAction)) {
            $nonRelevantMessage = call_user_func([$this, $isRelevantAction], $idx, $allElements, $request);
        } else {
            $nonRelevantMessage = count($idx) != 0 || $allElements; // at least one item is selected
        }

        if (!$nonRelevantMessage) { // default non relevant message (if false of null)
            $nonRelevantMessage = 'flash_batch_empty';
        }

        $datagrid = $this->admin->getDatagrid();
        $datagrid->buildPager();

        if (true !== $nonRelevantMessage) {
            $this->addFlash('sonata_flash_info', $nonRelevantMessage);

            return new RedirectResponse(
                $this->admin->generateUrl(
                    'list',
                    ['filter' => $this->admin->getFilterParameters()]
                )
            );
        }

        $askConfirmation = isset($batchActions[$action]['ask_confirmation']) ?
            $batchActions[$action]['ask_confirmation'] :
            true;

        if ($askConfirmation && $confirmation != 'ok') {
            $actionLabel = $batchActions[$action]['label'];
            $batchTranslationDomain = isset($batchActions[$action]['translation_domain']) ?
                $batchActions[$action]['translation_domain'] :
                $this->admin->getTranslationDomain();

            $formView = $datagrid->getForm()->createView();
            $this->setFormTheme($formView, $this->admin->getFilterTheme());

            return $this->render($this->admin->getTemplate('batch_confirmation'), [
                'action' => 'list',
                'action_label' => $actionLabel,
                'batch_translation_domain' => $batchTranslationDomain,
                'datagrid' => $datagrid,
                'form' => $formView,
                'data' => $data,
                'csrf_token' => $this->getCsrfToken('sonata.batch'),
            ], null);
        }

        // execute the action, batchActionXxxxx
        if (strpos(sprintf('batchAction%s', $camelizedAction), 'Estado') !== false) {
            $finalAction = "batchActionEstadosUpdate";
        } else {
            $finalAction = sprintf('batchAction%s', $camelizedAction);
        }
        if (!is_callable([$this, $finalAction])) {
            throw new \RuntimeException(sprintf('A `%s::%s` method must be callable', get_class($this), $finalAction));
        }

        $query = $datagrid->getQuery();

        $query->setFirstResult(null);
        $query->setMaxResults(null);

        $this->admin->preBatchAction($action, $query, $idx, $allElements);

        if (count($idx) > 0) {
            $this->admin->getModelManager()->addIdentifiersToQuery($this->admin->getClass(), $query, $idx);
        } elseif (!$allElements) {
            $query = null;
        }

        return call_user_func([$this, $finalAction], $query, $request);
    }

    public function batchActionEstados(ProxyQueryInterface $selectedModelQuery, Request $request = null, $status_name)
    {
        $this->admin->checkAccess('edit');
        $this->admin->checkAccess('delete');

        $modelManager = $this->admin->getModelManager();

        if ($request->get('all_elements') != 'on') {

            foreach ($request->get('idx') as $idx) {
                $target = $modelManager->find($this->admin->getClass(), $idx);

                if ($target === null) {
                    $this->addFlash('sonata_flash_info', 'flash_batch_merge_no_target');

                    return new RedirectResponse(
                        $this->admin->generateUrl('list', [
                            'filter' => $this->admin->getFilterParameters()
                        ])
                    );
                }
            }
        }

        $selectedModels = $selectedModelQuery->execute();
        $parsedStatus = str_replace('Estado_', '', $status_name);


        try {
            $estado = $this->container->get('doctrine')->getRepository(Estado::class)->findOneBy([
                'name' => $parsedStatus
            ]);

            if ($estado) {
                foreach ($selectedModels as $selectedModel) {
                    if ($selectedModel->getEstado()->getName() != $estado->getName()) {
                        foreach ($selectedModel->getUserData() as $d) {
                            $userData = $d;
                            $userEmail = $d->getEmail();
                        }

                        $this->sendEmails($selectedModel, $parsedStatus);
                        $selectedModel->setEstado($estado);

                        $this->saveNota($estado, $userEmail, $selectedModel, 'Batch: Estado cambiado a: ' . $estado);
                        $modelManager->update($selectedModel);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', $e->getMessage());

            return new RedirectResponse(
                $this->admin->generateUrl('list', [
                    'filter' => $this->admin->getFilterParameters()
                ])
            );
        }

        $this->addFlash('sonata_flash_success', 'Estados actualizados con Exito');

        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters()
            ])
        );
    }

    public function batchActionEstadosUpdate(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        return $this->batchActionEstados($selectedModelQuery, $request, ucfirst($request->get('action')));
    }

    /*public function batchActionCancelado(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        return $this->batchActionEstados($selectedModelQuery, $request, ucfirst($request->get('action')));
    }

    public function batchActionProcesando(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        return $this->batchActionEstados($selectedModelQuery, $request, ucfirst($request->get('action')));
    }*/

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     *
     * @param FormView $formView
     * @param string $theme
     */
    private function setFormTheme(FormView $formView, $theme)
    {
        $twig = $this->get('twig');

        try {
            $twig
                ->getRuntime('Symfony\Bridge\Twig\Form\TwigRenderer')
                ->setTheme($formView, $theme);
        } catch (\Twig_Error_Runtime $e) {
            // BC for Symfony < 3.2 where this runtime not exists
            $twig
                ->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')
                ->renderer
                ->setTheme($formView, $theme);
        }
    }

    public function saveNota($estado, $userEmail, $order, $msg)
    {
        $nota = new Nota();
        $nota->setEstado($estado);
        $nota->setMailUsuario($userEmail);
        $nota->setOrder($order);
        $nota->setNota($msg);
        $this->getDoctrine()->getManager()->persist($nota);
        $this->getDoctrine()->getManager()->flush();
    }
}
