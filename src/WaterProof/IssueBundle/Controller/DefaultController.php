<?php

namespace WaterProof\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use WaterProof\IssueBundle\Entity\Issue;

class DefaultController extends Controller
{
    /**
     * @Route("/issues", name="issues")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('WaterProofIssueBundle:Issue')->createQueryBuilder('i');


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return array('pagination' => $pagination);
    }

    /**
     * @Route("/issues/create", name="issue_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $issue = new Issue();

        $form = $this->createFormBuilder($issue)
            ->add('title', 'text')
            ->add('content', 'textarea', array('attr' => array('class' => 'summernoteTextArea')))
            ->add('deadlineAt', 'datetime', array(
                'widget' => 'single_text',
                'date_format' => 'd/m/Y H:i',
                'format' => 'd/m/Y H:i',
                'required' => false,
                'label' => 'Deadline', 'attr' => array('class' => 'date')))
            ->add('priority', 'choice', array('required' => false, 'choices' => array(
                1, 2, 3, 4, 5
            )))
            ->add('custom1', 'text', array('required' => false, 'label' => 'N° prog. ICR'))
            ->add('custom2', 'text', array('required' => false, 'label' => 'N° OASYS'))
            ->add('save', 'submit', array('attr' => array('class' => 'btn btn-primary')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $issue->setOwner($this->getUser());
            $em->persist($issue);
            $em->flush();

            return $this->redirect($this->generateUrl('issues'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/issues/edit/{id}", name="issue_edit")
     * @Template()
     */
    public function editAction($id)
    {
        return array();
    }

    /**
     * @Route("/issues/delete/{id}", name="issue_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$issue = $em->getRepository('WaterProofIssueBundle:Issue')->find($id)){
            throw new NotFoundHttpException();
        }

        if($issue->getOwner()->getId() <> $this->getUser()->getId()){
            throw new AccessDeniedException();
        }
        $em->remove($issue);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Issue has been deleted'
        );

        return $this->redirect($this->generateUrl('issues'));
    }


    /**
     * @Route("/issues/{id}", name="issue_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository('WaterProofIssueBundle:Issue')->find($id);

        return array('issue' => $issue);
    }
}
