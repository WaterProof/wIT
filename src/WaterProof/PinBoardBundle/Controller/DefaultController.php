<?php

namespace WaterProof\PinBoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use WaterProof\PinBoardBundle\Entity\Pin;

class DefaultController extends Controller
{
    /**
     * @Route("/pin-board", name="pins")
     * @Template()
     */
    public function indexAction()
    {
        $queryBuilder = $this->getDoctrine()->getManager()->getRepository('WaterProofPinBoardBundle:Pin')->createQueryBuilder('p');
        $queryBuilder->where('p.owner = :user')->setParameter('user', $this->getUser());
        $pins = $queryBuilder->getQuery()->execute();
        return array('pins' => $pins);
    }

    /**
     * @Route("/pin-board/create", name="pin_create")
     * @Template
     */
    public function createAction(Request $request)
    {
        $pin = new Pin();

        $form = $this->createFormBuilder($pin)
            ->add('title', 'text')
            ->add('content', 'textarea', array('attr' => array('class'=> 'summernoteTextArea')))
            ->add('save', 'submit', array('attr' => array('class' => 'btn btn-primary')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $pin->setOwner($this->getUser());
            $em->persist($pin);
            $em->flush();

            return $this->redirect($this->generateUrl('pin_index'));
        }

        return  array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/pin-board/delete/{id}", name="pin_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$pin = $em->getRepository('WaterProofPinBoardBundle:Pin')->find($id)){
        throw new NotFoundHttpException();
        }

        if($pin->getOwner()->getId() <> $this->getUser()->getId()){
            throw new AccessDeniedException();
        }
        $em->remove($pin);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Pin has been removed'
        );

        return $this->redirect($this->generateUrl('pin_index'));
    }
}
