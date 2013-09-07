<?php

namespace RCS\Bundle\DataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RCS\Bundle\DataBundle\Entity\Report;
use RCS\Bundle\DataBundle\Form\ReportType;

/**
 * Report controller.
 *
 * @Route("/report")
 */
class ReportController extends BaseController
{
    /**
     * Lists all Report entities.
     *
     * @Route("/", name="report")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        if(!$this->hasPermission('ROLE_USER'))
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        if($this->hasPermission('ROLE_ADMIN'))
            $entities = $em->getRepository('RCSDataBundle:Report')->findAll();
        else
        {
            $entities = $em->getRepository('RCSDataBundle:Report')->findBy(array(
                'reporter' => $this->getUser()
            ));
        }

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Report entity.
     *
     * @Route("/", name="report_create")
     * @Method("POST")
     * @Template("RCSDataBundle:Report:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if(!$this->hasPermission('ROLE_USER'))
            throw new AccessDeniedException();

        $entity  = new Report();
        $form = $this->createForm(new ReportType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setReporter($this->getUser());

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('report'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Report entity.
     *
     * @Route("/new", name="report_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if(!$this->hasPermission('ROLE_USER'))
            throw new AccessDeniedException();

        $entity = new Report();
        $form   = $this->createForm(new ReportType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/map", name="report_map")
     * @Template()
     */
    public function mapAction()
    {
        return array(
            'center' => array('latitude' => 38.02931, 'longitude' => -78.47668)
        );
    }

    /**
     * @Route("/graph", name="report_graph")
     * @Template()
     */
    public function graphAction()
    {
        $sites = $this->em->getRepository('RCSDataBundle:Site')->findBy(array(), array('name' => 'ASC'));

        return array(
            'sites' => $sites
        );
    }

    /**
     * @Route("/data", name="report_data")
     * @Template()
     */
    public function dataAction()
    {
        $request = $this->getRequest()->query;

        $data = array();

        $allowedPlots = array(
            'turbidityNtu', 'temperatureC',
            'dissolvedOxygenPpm', 'ph',
            'airTemperatureC',
            'participants',
        );

        if($request->has('plot') && $request->has('resolution'))
        {
            if(in_array($request->get('plot'), $allowedPlots))
                $field = $request->get('plot');
            else
                return new JsonResponse(array('error' => 'Invalid search arguments.'));

            switch($request->get('resolution'))
            {
                case 'year':
                case 'month':
                case 'day':
                    $resolution = $request->get('resolution');
                    break;
                default:
                    return new JsonResponse(array('error' => 'Invalid search arguments.'));
            }

            $qb = $this->em->createQueryBuilder();
            $qb
                ->select('r')
                ->from('RCS\Bundle\DataBundle\Entity\Report', 'r')
                ->where($qb->expr()->isNotNull('r.' . $field))
            ;

            if($request->has('site'))
            {
                $site = $request->getInt('site');
                $qb->andWhere($qb->expr()->eq('r.site', $site));
            }

            $qb->orderBy('r.timestamp', 'ASC');

            $reports = $qb->getQuery()->execute();

            $rawDataByDate = array();
            $reportsByDate = array();

            foreach($reports as $report)
            {
                $dateString = '';

                switch($resolution)
                {
                    case 'year':
                        $dateString .= $report->getYear() . '-01-01';
                        break;
                    case 'month':
                        $dateString .= $report->getYear() . '-' . str_pad($report->getMonth(), 2, '0', STR_PAD_LEFT) . '-01';
                        break;
                    case 'day':
                        $dateString .= $report->getYear() . '-' . str_pad($report->getMonth(), 2, '0', STR_PAD_LEFT) . '-' . str_pad($report->getDay(), 2, '0', STR_PAD_LEFT);
                        break;
                }

                $rawDataByDate[$dateString][] = $report->get($field);
                $reportsByDate[$dateString][] = $report->getId();
            }

            switch($field)
            {
                // average values
                case 'turbidityNtu':
                case 'turbidityJtu':
                case 'temperatureC':
                case 'dissolvedOxygenPpm':
                case 'ph':
                case 'airTemperatureC':
                case 'participants':
                    foreach($rawDataByDate as $dateString=>$values)
                    {
                        $date = \DateTime::createFromFormat('Y-m-d', $dateString);

                        $data[] = array(
                            'x' => $date->format('U'),
                            'y' => array_sum($values) / count($values),
                            'reports' => $reportsByDate[$dateString],
                        );
                    }
                    break;

                // total values
                case '':
                    foreach($rawDataByDate as $dateString=>$values)
                    {
                        $date = \DateTime::createFromFormat('Y-m-d', $dateString);

                        $data[] = array(
                            'x' => $date->format('U'),
                            'y' => array_sum($values),
                            'reports' => $reportsByDate[$dateString],
                        );
                    }
                    break;
            }

            return new JsonResponse(array(
                'resolution' => $resolution,
                'data' => $data
            ));
        }
        else
        {
            $reports = $this->em->getRepository('RCS\Bundle\DataBundle\Entity\Report')->findAll();

            foreach($reports as $report)
            {
                $data[] = array(
                    'siteId' => ($report->getSite() ? $report->getSite()->getId() : null),
                    'timestamp' => $report->getTimestamp(),
                    'turbidtyNtu' => $report->getTurbidityNtu(),
                    'temperatureC' => $report->getTemperatureC(),
                    'dissolvedOxygenPpm' => $report->getDissolvedOxygenPpm(),
                    'ph' => $report->getPh(),
                    'airTemperatureC' => $report->getAirTemperatureC(),
                );
            }

            return new JsonResponse(array(
                'data' => $data
            ));
        }
    }

    /**
     * Finds and displays a Report entity.
     *
     * @Route("/{id}", name="report_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RCSDataBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Report entity.
     *
     * @Route("/{id}/edit", name="report_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        if(!$this->hasPermission('ROLE_USER'))
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RCSDataBundle:Report')->find($id);

        if(!($this->hasPermission('ROLE_ADMIN') || $entity->getReporter()->getId() == $this->getUser()->getId()))
            throw new AccessDeniedException();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $editForm = $this->createForm(new ReportType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Report entity.
     *
     * @Route("/{id}", name="report_update")
     * @Method("PUT")
     * @Template("RCSDataBundle:Report:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if(!$this->hasPermission('ROLE_USER'))
            throw new AccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RCSDataBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        if(!($this->hasPermission('ROLE_ADMIN') || $entity->getReporter()->getId() == $this->getUser()->getId()))
            throw new AccessDeniedException();

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReportType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('report'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Report entity.
     *
     * @Route("/{id}", name="report_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        if(!$this->hasPermission('ROLE_USER'))
            throw new AccessDeniedException();

        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RCSDataBundle:Report')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Report entity.');
            }

            if(!($this->hasPermission('ROLE_ADMIN') || $entity->getReporter()->getId() == $this->getUser()->getId()))
                throw new AccessDeniedException();

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('report'));
    }

    /**
     * Creates a form to delete a Report entity by id.
     *
     * @param mixed $id The entity id
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
