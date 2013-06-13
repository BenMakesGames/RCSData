<?php
namespace RCS\Bundle\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseController extends Controller
{
    /** @var \Doctrine\ORM\EntityManager */ protected $em;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->em = $this->getDoctrine()->getManager();
    }
}