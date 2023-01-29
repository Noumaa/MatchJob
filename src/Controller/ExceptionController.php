<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ExceptionController extends AbstractController {
    
    public function showException(Throwable $exception): Response
    {
        $template = 'error.html.twig';
        $code = 500;

        if ($exception instanceof NotFoundHttpException) {
            $template = '404.html.twig';
            $code = 404;
        }

        return new Response($this->renderView('exception/'.$template, [
            'message' => $exception->getMessage(),
        ]), $code);
    }
}