<?php


namespace Esiea\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Blog controller.
 */
class BlogController extends Controller
{
  /**
   * Show a blog entry
   */
  public function showAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();

    $blog = $em->getRepository('EsieaBlogBundle:Blog')->find($id);

    if (!$blog) {
      throw $this->createNotFoundException('Unable to find Blog post.');
    }

    $comments = $em->getRepository('EsieaBlogBundle:Comment')
      ->getCommentsForBlog($blog->getId());

    return $this->render('EsieaBlogBundle:Blog:show.html.twig',
                         array(
                               'blog'      => $blog,
                               'comments'  => $comments
                               ));
  }
}
