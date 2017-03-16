<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use FOS\RestBundle\View\View;

class UserController extends Controller
{
    /**
     * @rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $users;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/users/{user_id}")
     */
    public function getUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($request->get('user_id'));

        if (!$user) {
            return View::create(array('error' => 'User not found'), Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/users")
     */
    public function postUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->submit($form);

        if ($form->isValid()) {
            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Delete("/users/{id}")
     */
    public function removeUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($request->get('id'));

        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/users/{id}")
     */
    public function updateUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    public function updateUser(Request $request, $clearMissing)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($request->get('id'));

        if (!$user) {
            return View::create(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em->merge($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }
}
