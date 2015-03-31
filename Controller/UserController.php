<?php

/*
 * This class is copied and adapted from BeelabUserBundle
 * https://github.com/Bee-Lab/BeelabUserBundle
 */

namespace Performer\RerUserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("", name="user")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $paginator = $this->get('performer_rer_user.manager')->getList($request->query->get('page', 1), 20);

        return ['paginator' => $paginator];
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", name="user_show")
     * @Template()
     */
    public function showAction($id)
    {
        $user = $this->get('performer_rer_user.manager')->get($id);
        $deleteForm = $this->createDeleteForm($user->getId());

        return [
            'user'        => $user,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $user = $this->get('performer_rer_user.manager')->getInstance();
        $form = $this->createForm('rer_user', $user, ['validation_groups' => ['create']]);
        if ($form->handleRequest($request)->isValid()) {
            $this->get('performer_rer_user.manager')->create($user);

            return $this->redirect($this->generateUrl('user_show', ['id' => $user->getId()]));
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $user = $this->get('performer_rer_user.manager')->get($id);
        $editForm = $this->createForm('rer_user', $user, ['validation_groups' => ['update']]);
        if ($editForm->handleRequest($request)->isValid()) {
            $this->get('performer_rer_user.manager')->update($user);

            return $this->redirect($this->generateUrl('user_show', ['id' => $user->getId()]));
        }
        $deleteForm = $this->createDeleteForm($user->getId());

        return [
            'user'        => $user,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}/delete", name="user_delete")
     * @Method("POST")
     */
    public function deleteAction($id, Request $request)
    {
        $user = $this->get('performer_rer_user.manager')->get($id);
        $form = $this->createDeleteForm($user->getId());
        if ($form->handleRequest($request)->isValid()) {
            $this->get('performer_rer_user.manager')->delete($user);
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Create Delete form
     *
     * @param  integer $id
     * @return Form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(['id' => $id])
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
