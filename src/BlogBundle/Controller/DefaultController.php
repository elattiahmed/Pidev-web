<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Blog;
use BlogBundle\Entity\Blog2;
use BlogBundle\Entity\BlogLike;
use BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
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
            $blog->setAccept(0);
            $blog->setDateCreation(new \DateTime());

            $em->persist($blog);

            $em->flush();

            return $this->redirectToRoute('blog_show', array('id' => $blog->getId()));
        }

        return $this->render('BlogBundle:Default:addnewblog.html.twig', array(
            'blog' => $blog,
            'form' => $form->createView(),
        ));
    }

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
            4/*limit per page*/
        );
        $query = $em->createQuery('SELECT V From BlogBundle:Blog V order by V.dateCreation desc ')->setMaxResults(3);
        $blogmax = $query->getResult();
        $category = $em->getRepository('BlogBundle:Categories')->findAll();

        return $this->render('BlogBundle:Default:index.html.twig', array(
            'blogs' => $pagination,
            'blogsmax' => $blogmax,
            'Cat' => $category

        ));
    }
    public function ByCategorieAction(Request $request,$cat)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryId = $em->getRepository('BlogBundle:Categories')->findByName($cat);

        $blog = $em->getRepository('BlogBundle:Blog')->findByCategorie($categoryId);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $blog,
            $request->query->getInt('page', 1)/*page number*/,
            8/*limit per page*/
        );
        $query = $em->createQuery('SELECT V From BlogBundle:Blog V order by V.dateCreation desc ')->setMaxResults(3);
        $blogmax = $query->getResult();
        $category = $em->getRepository('BlogBundle:Categories')->findAll();

        return $this->render('BlogBundle:Default:index.html.twig', array(
            'blogs' => $pagination,
            'blogsmax' => $blogmax,
            'Cat' => $category

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
        $numlike = $em->getRepository('BlogBundle:BlogLike')->findByBlog($post);
        $likeornot = $em->getRepository('BlogBundle:BlogLike')->findByacceptlike($post);
        $numlikes = count($numlike);
        $numberofcomments = count($comments);
        $likeornotttt = count($likeornot);
        $category = $em->getRepository('BlogBundle:Categories')->findAll();

        return $this->render('BlogBundle:Default:show.html.twig', array(
            'blog' => $blog,
            'delete_form' => $deleteForm->createView(),
            'numberofcomments' => $numberofcomments,
            'comments' => $comments,
            'likeornot' => $likeornotttt,
            'numlikes'=>$numlikes,
            'arr' => $arr,
            'Cat' => $category
        ));
    }
    public function likeBlogAction($id)
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('BlogBundle:Blog')->find($id);

        $love = new BlogLike();

        $love->setUser($user);
        $love->setAcceptlike(1);
        $post->setLikesnumber($post->getLikesnumber() + 1);
        $love->setBlog($post);
        $em->persist($love);
        $em->flush();

        return $this->redirectToRoute('blog_show', array('id' => $post->getId()));
    }
    public function dislikeblogAction($id)
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('BlogBundle:Blog')->find($id);

        $love = new BlogLike();

        $love->setUser($user);
        $love->setAcceptlike(0);
        $post->setLikesnumber($post->getLikesnumber() - 1);
        $love->setBlog($post);
        $em->persist($love);
        $em->flush();

        return $this->redirectToRoute('blog_show', array('id' => $post->getId()));
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

    public function RechercheBlogAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities = $em->getRepository('BlogBundle:Blog')->AjaxRecherche($requestString);
        if (!$entities) {
            $result['entities']['error'] = "there is no Blog ";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));

    }


    public function getRealEntities($entities)
    {
        foreach ($entities as $entity) {
            $realEntities[$entity->getId()] = [
                $entity->getTitle(),
                $entity->getContent(),
                $entity->getCategorie(),
                $entity->getImage(),
                $entity->getAuthor()->getUsername(),
                $entity->getDateCreation()->format("Y-m-d"),
                $entity->getLikesnumber(),
                $entity->getRepliesnumber()

            ];

        }
        return $realEntities;
    }

}
