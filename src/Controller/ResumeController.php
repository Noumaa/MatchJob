<?php

namespace App\Controller;

use App\Entity\Resume;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends AbstractController
{

    #[IsGranted("ROLE_PERSON")]
    #[Route('/modifier-son-cv', name: 'app_resume_edit')]
    public function edit(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $resume = $user->getResume();

        if ($resume == null) $resume = new Resume();

        return $this->render('resume/modifier-son-cv.html.twig', [
            'resume' => $resume,
        ]);
    }
}
