<?php

namespace LocationBundle\Controller;

use LocationBundle\Entity\Contrat;
use LocationBundle\Entity\Location;
use ShopBundle\Entity\Category;
use ShopBundle\Entity\Produit;
use ShopBundle\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

        $products = $this->getDoctrine()->getRepository('LocationBundle:Location')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );
        return $this->render('LocationBundle:Default:index.html.twig', ['locations' => $pagination]);
    }

    public function addAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $location = new Location();
        $form = $this->createFormBuilder($location)
            ->add('matricule', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('marque', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('model', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('dailyPrice', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('puissance', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('type', ChoiceType::class, [
                // looks for choices from this entity
                'choices' => [
                    'voiture'=>'voiture',
                    'bateaux'=>'bateaux'
                ],
                ])
            ->add('submit',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $matricule = $form['matricule']->getData();
            $marque = $form['marque']->getData();
            $model = $form['model']->getData();
            $category = $form['category']->getData();
            $dailyPrice = $form['dailyPrice']->getData();
            $puissance = $form['puissance']->getData();
            $type = $form['type']->getData();

            $location->setMatricule($matricule);
            $location->setCategory($category);
            $location->setDailyPrice($dailyPrice);
            $location->setMarque($marque);
            $location->setUtilisateur($user);
            $location->setModel($model);
            $location->setPuissance($puissance);
            $location->setType($type);


            $sn = $this->getDoctrine()->getManager();
            $sn->persist($location);
            $sn->flush();

            return $this->redirectToRoute('location_homepage');

        }


        return $this->render('LocationBundle:Default:add.html.twig', array('form' => $form->createView()));
    }

    public function louerAction($id,Request $request){

        $sn = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $location = $sn->getRepository('LocationBundle:Location')->find($id);
        $contrat = new Contrat();
        $form = $this->createFormBuilder($contrat)
            ->add('dateDebutLocation', DateType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('dateFinLocation', DateType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('type', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('submit',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $dateDebutLocation = $form['dateDebutLocation']->getData();
            $dateFinLocation = $form['dateFinLocation']->getData();
            $type = $form['type']->getData();
            $now = new\DateTime('now');

            $contrat->setDateDebutLocation($dateDebutLocation);
            $contrat->setDateFinLocation($dateFinLocation);
            $contrat->setDate($now);
            $contrat->setType($type);
            $contrat->setUtilisateur($user);
            $contrat->setLocation($location);



            $sn = $this->getDoctrine()->getManager();
            $sn->persist($contrat);
            $sn->flush();

            return $this->redirectToRoute('location_homepage');

        }

        return $this->render('LocationBundle:Default:CreateContrat.html.twig', array('form' => $form->createView()));


    }


    public function editAction($id, Request $request){

        $location = $this->getDoctrine()
            ->getRepository('LocationBundle:Location')
            ->find($id);




        $location->setMatricule($location->getMatricule());
        $location->setCategory($location->getCategory());
        $location->setDailyPrice($location->getDailyPrice());
        $location->setMarque($location->getMarque());
        $location->setModel($location->getModel());
        $location->setPuissance($location->getPuissance());
        $location->setType($location->getType());


        $form = $this->createFormBuilder($location)
            ->add('matricule', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('marque', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('model', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('dailyPrice', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('puissance', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('type', ChoiceType::class, [
                // looks for choices from this entity
                'choices' => [
                    'voiture'=>'voiture',
                    'bateaux'=>'bateaux'
                ],
            ])
            ->add('submit',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);




        if ($form->isSubmitted() && $form->isValid()) {

            $sn = $this->getDoctrine()->getManager();
            $location = $sn->getRepository('LocationBundle:Location')->find($id);
            $matricule = $form['matricule']->getData();
            $marque = $form['marque']->getData();
            $model = $form['model']->getData();
            $category = $form['category']->getData();
            $dailyPrice = $form['dailyPrice']->getData();
            $puissance = $form['puissance']->getData();
            $type = $form['type']->getData();

            $location->setMatricule($matricule);
            $location->setCategory($category);
            $location->setDailyPrice($dailyPrice);
            $location->setMarque($marque);
            $location->setModel($model);
            $location->setPuissance($puissance);
            $location->setType($type);

            $sn->persist($location);
            $sn->flush();

            return $this->redirectToRoute('location_homepage');

        }


        return $this->render('@Location/Default/edit.html.twig', array(

            'location'=>$location,
            'form'=>$form->createView()
        ));

    }

    public function deleteAction($id){

        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $sn = $this->getDoctrine()->getManager();
            $location = $sn->getRepository('LocationBundle:Location')->find($id);
            $sn->remove($location);
            $sn->flush();

            return $this->redirectToRoute('location_homepage');
        }
        else{
            return $this->redirectToRoute('fos_user_security_login');
        }


    }

}
