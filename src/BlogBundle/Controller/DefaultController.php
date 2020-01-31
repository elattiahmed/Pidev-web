<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Blog;
use BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{


    /**
     * Lists all blog entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('BlogBundle:Blog')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $blog,
            $request->query->getInt('page', 1)/*page number*/,
            8/*limit per page*/
        );
        $query = $em->createQuery('SELECT V From BlogBundle:Blog V order by V.dateCreation desc ')->setMaxResults(3);
        $blogmax = $query->getResult();

        return $this->render('BlogBundle:Default:index.html.twig', array(
            'blogs' => $pagination,
            'blogsmax' => $blogmax,
        ));
    }
    public function newAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm('BlogBundle\Form\BlogType', $blog);
        $form->handleRequest($request);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $blog->setAuthor($user);
            $blog->setRepliesnumber(0);

            $blog->setDateCreation(new \DateTime());

            $em->persist($blog);

            $em->flush();

            return $this->redirectToRoute('blog_show', array('id' => $blog->getId()));
        }

        return $this->render('BlogBundle:Default:new.html.twig', array(
            'blog' => $blog,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a blog entity.
     *
     */
    public function showAction(Request $request, Blog $blog)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $post = $em->getRepository('BlogBundle:Blog')->find($blog);
        $arr = array();
        $deleteForm = $this->createDeleteForm($blog);
        if ($request->isMethod('post')) {
            $comment = new Comment();
            $comment->setUser($user);
            $comment->setBlog($post);
            $comment->setContent($request->get('comment-content'));
            $comment->setPublishdate(new \DateTime('now'));
            $post->setRepliesnumber($post->getRepliesnumber() + 1);
            $em->persist($post);
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('blog_show', array('id' => $blog->getId()));
        }
        $comments = $em->getRepository('BlogBundle:Comment')->findByBlog($post);
        $numberofcomments = count($comments);
        return $this->render('BlogBundle:Default:show.html.twig', array(
            'blog' => $blog,
            'delete_form' => $deleteForm->createView(),
            'numberofcomments' => $numberofcomments,
            'comments' => $comments,
            'arr' => $arr
        ));
    }
    /**
     * Creates a form to delete a blog entity.
     *
     * @param Blog $blog The blog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Blog $blog)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blog_delete', array('id' => $blog->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


}
