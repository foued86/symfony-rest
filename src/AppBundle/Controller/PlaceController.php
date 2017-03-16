<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Place;
use AppBundle\Form\PlaceType;

class PlaceController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/places")
     */
    public function getPlacesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $places = $em->getRepository('AppBundle:Place')->findAll();

        return $places;
    }

    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Get("/places/{id}")
     */
    public function getPlaceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $place = $em->getRepository('AppBundle:Place')->find($request->get('id'));

        if (!$place) {
            return View::create(array('error' => 'Place not found'), Response::HTTP_NOT_FOUND);
        }

        return $place;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"place"})
     * @Rest\Post("/places")
     */
    public function postPlaceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $place->setName($request->get('name'));
            $place->setAddress($request->get('address'));
            $em->persist($place);
            $em->flush();
            return $place;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/places/{id}")
     */
    public function removePlaceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $place = $em->getRepository('AppBundle:Place')->find($request->get('id'));

        if ($place) {
            $em->remove($place);
            $em->flush();
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/places/{id}")
     */
    public function updatePlaceAction(Request $request)
    {
        return $this->updatePlace($request, true);
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/places/{id}")
     */
    public function patchPlaceAction(Request $request)
    {
        return $this->updatePlace($request, false);
    }

    public function updatePlace(Request $request, $clearingMissing)
    {
        $em = $this->getDoctrine()->getManager();

        $place = $em->getRepository('AppBundle:Place')->find($request->get('id'));

        if (!$place) {
            return View::create(['error' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all(), $clearingMissing);

        if ($form->isValid()) {
            $em->merge($place);
            $em->flush();
            return $place;
        } else {
            return $form;
        }
    }
}
