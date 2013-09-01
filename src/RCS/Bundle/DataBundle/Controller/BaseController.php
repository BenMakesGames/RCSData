<?php
namespace RCS\Bundle\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseController extends Controller
{
    /** @var \Doctrine\ORM\EntityManager */ protected $em;
    protected $securityContext;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->em = $this->getDoctrine()->getManager();
        $this->securityContext = $container->get('security.context');
    }

    public function hasPermission($permission, $object = null)
    {
        return $this->securityContext->isGranted($permission, $object);
    }
}