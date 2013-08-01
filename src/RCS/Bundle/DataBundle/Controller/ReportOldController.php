<?php

namespace RCS\Bundle\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RCS\Bundle\DataBundle\Entity\Report;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReportOldController extends BaseController
{
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
            ->add('participants')
            ->add('precipitationDescription')
            ->add('landDescription')
            ->add('turbidityNtu')
            ->add('temperatureC')
            ->add('dissolvedOxygenPpm')
            ->add('ph', 'number')
            ->add('airTemperatureC')
            ->add('rcsTestKitUse')
            ->getForm()
        ;
    }
}
