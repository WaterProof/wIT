<?php

namespace WaterProof\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WaterProof\WorkflowBundle\Workflow;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @Template()
     */
    public function indexAction()
    {
        $parameters = array('stats' => array());
        // récupérer le nombre d'issue dans chacun des états
        // - New: status = new
        // - Pending: status = pending + current assignee = me (or my team?)
        // - Delegated: status = pending + current assignee not in my team
        // - Closed: status = closed

        // hot ratio:
        //  - 1: deadline dans moins de 24h
        //  - 2: deadline dans entre 24h et 48h
        //  - 3: deadline dans plus de 48h


        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('WaterProofIssueBundle:Issue')->createQueryBuilder('i');
        $queryBuilder->where('i.status = :status')->setParameter('status', Workflow::STATUS_NEW);
        $queryBuilder->select('count(i.id)');
        $parameters['stats']['new'] = $queryBuilder->getQuery()->getSingleScalarResult();

        $queryBuilder = $em->getRepository('WaterProofIssueBundle:Issue')->createQueryBuilder('i');
        $queryBuilder->where('i.status = :status')->setParameter('status', Workflow::STATUS_PENDING);
        $queryBuilder->select('count(i.id)');
        $parameters['stats']['pending']['total'] = $queryBuilder->getQuery()->getSingleScalarResult();
        $parameters['stats']['pending']['hot'] = 0;

        $parameters['stats']['delegated']['total'] = 0;
        $parameters['stats']['delegated']['hot'] = 0;


        $queryBuilder = $em->getRepository('WaterProofIssueBundle:Issue')->createQueryBuilder('i');
        $queryBuilder->select('count(i.id)');
        $queryBuilder->where('i.status = :status')->setParameter('status', Workflow::STATUS_CLOSED);
        $queryBuilder->andWhere('i.closedAt BETWEEN :closed_start AND :closed_end')
            ->setParameter('closed_start', date('Y-m-01 00:00:00'))
            ->setParameter('closed_end', date('Y-m-t 23:59:59'));
        $parameters['stats']['closed'] = $queryBuilder->getQuery()->getSingleScalarResult();


        return $parameters;
    }
}
