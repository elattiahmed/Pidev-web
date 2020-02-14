<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\Category;
use ShopBundle\Entity\Produit;
use ShopBundle\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function myproductAction(){

    }
    public function addAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $produit = new Produit();
        $form = $this->createFormBuilder($produit)
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('quantity', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('prix', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('imageId', FileType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
                'choice_label' => 'category',])
            ->add('region', EntityType::class, [
                // looks for choices from this entity
                'class' => Region::class,
                'choice_label' => 'region',])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $produit->getImageId();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored

            $file->move(
                $this->getParameter('images_shop'),
                $fileName
            );


            $produit->setImageId($fileName);

            $nom = $form['nom']->getData();
            $description = $form['description']->getData();
            $stock = $form['quantity']->getData();
            $prix = $form['prix']->getData();
            $cat = $form['category']->getData();
            $reg = $form['region']->getData();
            $now = new\DateTime('now');

            $produit->setNom($nom);
            $produit->setDescription($description);
            $produit->setQuantity($stock);
            $produit->setPrix($prix);
            $produit->setUtilisateur($user);
            $produit->setCategory($cat);
            $produit->setRegion($reg);
            $produit->setStars(0);
            $produit->setDate($now);

            $sn = $this->getDoctrine()->getManager();
            $sn->persist($produit);
            $sn->flush();

            $this->addFlash(
                'notice',
                'todo added'
            );

        }

        $panierlist = $this->getDoctrine()->getRepository('ShopBundle:Panier')->findByUser($user);
        $cat = $this->getDoctrine()->getRepository('ShopBundle:Category')->findAll();
        $region = $this->getDoctrine()->getRepository('ShopBundle:Region')->findAll();

        $count = count($panierlist);
        $total = 0;
        foreach ($panierlist as $prix) {

            $total = $total + $prix->getPrix();
        }



        return $this->render('ShopBundle:Default:add.html.twig', array(

            'cat' => $cat,
            'region' => $region,

            'form' => $form->createView(),'nbrp' => $count, 'panier' => $panierlist, 'total' => $total

        ));
    }

    public function indexAction(Request $request)
    {

        $products = $this->getDoctrine()->getRepository('ShopBundle:Produit')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );
        return $this->render('ShopBundle:Default:index.html.twig',['products' => $pagination]);
    }

    public function detailsAction($id)
    {

        $product = $this->getDoctrine()->getRepository('ShopBundle:Produit')->find($id);

        return $this->render('ShopBundle:Default:details.html.twig',['product' => $product]);
    }
}
