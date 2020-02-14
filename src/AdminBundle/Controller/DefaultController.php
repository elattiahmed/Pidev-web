<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    public function AfficherUserAction(){

        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();
        return $this->render('AdminBundle:Default:users.html.twig', array(

            'users'=>$todos
        ));

    }
    public function AfficherblogsAction(){

        $allblogs = $this->getDoctrine()
            ->getRepository('BlogBundle:Blog')
            ->findAll();
        return $this->render('AdminBundle:Default:blogs.html.twig', array(

            'blogs'=>$allblogs
        ));

    }
    public function AfficherMsgAction(){

        $todos = $this->getDoctrine()
            ->getRepository('ContacusBundle:Contact')
            ->findAll();
        return $this->render('AdminBundle:Default:inbox.html.twig', array(

            'msgs'=>$todos
        ));

    }
    public function StatusAction($id){
        $em = $this->getDoctrine()->getManager();

        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);

        if ($todos->isEnabled()==0){
            $todos->setEnabled(1);
            $em->persist($todos);
            $em->flush();
        }
        else {
            $todos->setEnabled(0);
            $em->persist($todos);
            $em->flush();
        }
          return  $this->redirectToRoute('admin_users');
    }

    public function RemoveAction($id, Request $request){
        $sn = $this->getDoctrine()->getManager();
        $todo = $sn->getRepository('BlogBundle:Blog')->find($id);
        $sn->remove($todo);
        $sn->flush();

        return $this->redirectToRoute('all_blog');

    }
    public function MsgDetaisAction($id, Request $request){
        $msg = $this->getDoctrine()
            ->getRepository('ContacusBundle:Contact')
            ->find($id);


        return $this->render('AdminBundle:Default:inboxdetais.html.twig', array(

            'msgdtais'=>$msg
        ));

    }

}
