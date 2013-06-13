<?php

namespace RCS\Bundle\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RCS\Bundle\DataBundle\Entity\Report;

/**
 * @Route("/report")
 */
class ReportController extends BaseController
{
    /**
     * @Route("/new", name="report_new")
     * @Template()
     */
    public function newAction()
    {
        $request = $this->get('request');

        $report = new Report();
        $report->setTimestamp(new \DateTime());

        $form = $this->createFormBuilder($report)
            ->add('latitude', 'text')
            ->add('longitude', 'text')
            ->getForm()
        ;

        $title = 'Make a Report';

        if($request->isMethod('POST'))
        {
            $form->bind($request);

            if($form->isValid())
            {
                $this->em->persist($report);
                $this->em->flush();

                $form = $this->buildReportForm(new Report());

                $form->get('latitude')->setData($report->getLatitude());
                $form->get('longitude')->setData($report->getLongitude());

                $this->get('session')->setFlash(
                    'notice',
                    'Your report has been saved.'
                );

                $title = 'Make Another Report';
            }
        }

        return array(
            'title' => $title,
            'form' => $form->createView(),
        );
    }

    private function buildReportForm($report)
    {
        return $this->createFormBuilder($report)
            ->add('latitude', 'text')
            ->add('longitude', 'text')
            ->getForm()
        ;
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
     * @Route("/data", name="report_data")
     * @Template()
     */
    public function dataAction()
    {
        $data = array();

        $reports = $this->em->getRepository('RCS\Bundle\DataBundle\Entity\Report')->findAll();
        foreach($reports as $report)
        {
            $data[] = array(
                'latitude' => $report->getLatitude(),
                'longitude' => $report->getLongitude(),
                'title' => 'test',
            );
        }

        return new JsonResponse(array(
            'reports' => $data
        ));
    }
}
