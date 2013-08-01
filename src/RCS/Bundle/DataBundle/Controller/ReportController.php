<?php

namespace RCS\Bundle\DataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RCSDataBundle:Report')->findAll();

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
        $entity  = new Report();
        $form = $this->createForm(new ReportType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('report_show', array('id' => $entity->getId())));
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
        return array();
    }

    /**
     * @Route("/data", name="report_data")
     * @Template()
     */
    public function dataAction()
    {
        $request = $this->get('request');

        $data = array();

        $allowedPlots = array(
            'turbidityNtu', 'temperatureC',
            'dissolvedOxygenPpm', 'ph',
            'airTemperatureC',
            'participants',
        );

        if($request->query->has('plot') && $request->query->has('resolution'))
        {
            if(in_array($request->get('plot'), $allowedPlots))
                $field = $request->get('plot');
            else
                return new BadRequestHttpException('Invalid search arguments.');

            switch($request->get('resolution'))
            {
                case 'year':
                case 'month':
                case 'day':
                    $resolution = $request->get('resolution');
                    break;
                default:
                    return new BadRequestHttpException('Invalid search arguments.');
            }

            $reports = $this->em->createQuery('
                SELECT r
                FROM RCSDataBundle:Report r
                WHERE r.' . $field . ' IS NOT NULL
            ')->getResult();

            $rawData = array();

            foreach($reports as $report)
            {
                $key = '';

                switch($resolution)
                {
                    case 'year':
                        $key .= $report->getYear();
                        break;
                    case 'month':
                        $key .= $report->getYear() . '-' . str_pad($report->getMonth(), 2, '0', STR_PAD_LEFT);
                        break;
                    case 'day':
                        $key .= $report->getYear() . '-' . str_pad($report->getMonth(), 2, '0', STR_PAD_LEFT) . '-' . str_pad($report->getDay(), 2, '0', STR_PAD_LEFT);
                        break;
                }

                $rawData[$key][] = $report->get($field);
            }

            switch($field)
            {
                // average values
                case 'turbidityNtu':
                case 'temperatureC':
                case 'dissolvedOxygenPpm':
                case 'ph':
                case 'airTemperatureC':
                case 'participants':
                    foreach($rawData as $key=>$values)
                    {
                        $data[] = array(
                            'date' => $key,
                            'value' => array_sum($values) / count($values)
                        );
                    }
                    break;

                // total values
                case '':
                    foreach($rawData as $key=>$values)
                    {
                        $data[] = array(
                            'date' => $key,
                            'value' => array_sum($values)
                        );
                    }
                    break;
            }
        }
        else
        {
            $reports = $this->em->getRepository('RCS\Bundle\DataBundle\Entity\Report')->findAll();

            foreach($reports as $report)
            {
                $data[] = array(
                    'latitude' => $report->getLatitude(),
                    'longitude' => $report->getLongitude(),
                    'timestamp' => $report->getTimestamp(),
                    'turbidtyNtu' => $report->getTurbidityNtu(),
                    'temperatureC' => $report->getTemperatureC(),
                    'dissolvedOxygenPpm' => $report->getDissolvedOxygenPpm(),
                    'ph' => $report->getPh(),
                    'airTemperatureC' => $report->getAirTemperatureC(),
                );
            }
        }

        return new JsonResponse(array(
            'data' => $data
        ));
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RCSDataBundle:Report')->find($id);

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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RCSDataBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReportType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('report_edit', array('id' => $id)));
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
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RCSDataBundle:Report')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Report entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('report'));
    }

    /**
     * Creates a form to delete a Report entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
