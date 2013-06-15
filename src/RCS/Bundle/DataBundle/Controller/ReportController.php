<?php

namespace RCS\Bundle\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RCS\Bundle\DataBundle\Entity\Report;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

        $form = $this->buildReportForm($report);

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
            ->add('ph', 'number')
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

        $reports = $this->em->getRepository('RCS\Bundle\DataBundle\Entity\Report')->findAll();

        if($request->query->has('plot') && $request->query->has('resolution'))
        {
            if($request->get('plot') == 'ph')
                $field = 'ph';
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
                case 'ph':
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
            foreach($reports as $report)
            {
                $data[] = array(
                    'latitude' => $report->getLatitude(),
                    'longitude' => $report->getLongitude(),
                    'ph' => $report->getPh(),
                    'title' => 'test',
                );
            }
        }

        return new JsonResponse(array(
            'data' => $data
        ));
    }
}
