<?php
// src/Esiea/BlogBundle/Controller/PageController.php

namespace Esiea\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Esiea\BlogBundle\Entity\Enquiry;
use Esiea\BlogBundle\Form\EnquiryType;

class PageController extends Controller
{
    public function indexAction()
    {
              $em = $this->getDoctrine()
                   ->getEntityManager();

        $blogs = $em->getRepository('EsieaBlogBundle:Blog')
                    ->getLatestBlogs();

        return $this->render('EsieaBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }

 	public function aboutAction()
    {
        return $this->render('EsieaBlogBundle:Page:about.html.twig');
    }





	  public function contactAction()
  {

  	 $enquiry = new Enquiry();
    $form = $this->createForm(new EnquiryType(), $enquiry);

    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid())
        {
          $message = \Swift_Message::newInstance()
            ->setSubject('Mail dun visiteur du site symfony')
            ->setFrom('nicolas.suor@gmail.com')
            ->setTo($this->container->getParameter('esiea_blog.emails.contact_email'))
            ->setBody($this->renderView('EsieaBlogBundle:Page:ContactEmail.txt.twig', array('enquiry' => $enquiry)));
          $this->get('mailer')->send($message);

          $this->get('session')-> getFlashBag()->set('formulaire', 'Votre demande de contact a bien été envoyé. merci!');

          // Redirect - This is important to prevent users re-posting
          // the form if they refresh the page
          return $this->redirect($this->generateUrl('EsieaBlogBundle_contact'));
        }
    }
    $enquiry = new Enquiry();
    $form = $this->createForm(new EnquiryType(), $enquiry);

    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid()) {
        // Perform some action, such as sending an email

        // Redirect - This is important to prevent users re-posting
        // the form if they refresh the page
        return $this->redirect($this->generateUrl('EsieaBlogBundle_contact'));
      }
    }

    return $this->render('EsieaBlogBundle:Page:contact.html.twig',
                         array(
                               'form' => $form->createView()
                               ));

  }


public function sidebarAction()
  {
    $em = $this->getDoctrine()
      ->getEntityManager();

    $tags = $em->getRepository('EsieaBlogBundle:Blog')
      ->getTags();

    $tagWeights = $em->getRepository('EsieaBlogBundle:Blog')
      ->getTagWeights($tags);

    $commentLimit   = $this->container
                           ->getParameter('esiea_blog.comments.latest_comment_limit');
    $latestComments = $em->getRepository('EsieaBlogBundle:Comment')
                         ->getLatestComments($commentLimit);

    return $this->render('EsieaBlogBundle:Page:sidebar.html.twig',
                         array(
                               'latestComments'    => $latestComments,
                               'tags' => $tagWeights
                               ));
  }

}




