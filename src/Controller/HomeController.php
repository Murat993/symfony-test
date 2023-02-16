<?php

namespace App\Controller;

use App\Model\Product\Create\Command;
use App\Model\Product\Create\Form;
use App\Model\Product\Create\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{

    /**
     * @Route("", name="home")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Handler $handler)
    {
        $command = new Command();

        $form = $this->createForm(Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Product created.');
                return $this->redirectToRoute('home');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}