<?php

namespace App\Controller;

use Fpdf\Fpdf;
use App\Entity\Resume\Course;
use App\Entity\Resume\Experience;
use App\Entity\Resume\Resume;
use App\Entity\Resume\Skill;
use App\Entity\User;
use App\Form\Resume\CourseType;
use App\Form\Resume\ExperienceType;
use App\Form\Resume\SkillType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends AbstractController
{

    #[IsGranted("ROLE_PERSON")]
    #[Route('/modifier-son-cv', name: 'app_resume_edit')]
    public function edit(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $resume = $user->getResume() === null ? new Resume() : $user->getResume();

        $courses = $resume->getCourses();
        $experiences = $resume->getExperiences();
        $skills = $resume->getSkills();

        $add_skill = $this->createForm(SkillType::class)->createView();
        $add_experience = $this->createForm(ExperienceType::class)->createView();
        $add_course = $this->createForm(CourseType::class)->createView();

        return $this->render('resume/modifier-son-cv.html.twig', [
            'resume' => $resume,

            'courses' => $courses,
            'experiences' => $experiences,
            'skills' => $skills,

            'add_skill' => $add_skill,
            'add_experience' => $add_experience,
            'add_course' => $add_course,
        ]);
    }

    public function getOrCreateResume(User $user, EntityManagerInterface $manager) {
        $resume = $user->getResume();

        if ($resume === null) {
            $resume = new Resume();
            $user->setResume($resume);

            $manager->persist($resume);
            $manager->flush();
        }

        return $resume;
    }

    #[IsGranted("ROLE_PERSON")]
    #[Route('/modifier-son-cv/ajouter-une-competence', name: 'app_resume_skill_add')]
    public function skillAdd(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $resume = $this->getOrCreateResume($user, $manager);

        $skill = new Skill();

        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $skill = $form->getData();
            $skill->setResume($resume);

            $manager->persist($skill);
            $manager->flush();
        }

        return $this->redirectToRoute("app_resume_edit");
    }

    #[IsGranted("ROLE_PERSON")]
    #[Route('/modifier-son-cv/ajouter-une-experience', name: 'app_resume_experience_add')]
    public function experienceAdd(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $resume = $this->getOrCreateResume($user, $manager);

        $experience = new Experience();

        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $experience = $form->getData();
            $experience->setResume($resume);

            $manager->persist($experience);
            $manager->flush();
        }

        return $this->redirectToRoute("app_resume_edit");
    }

    #[IsGranted("ROLE_PERSON")]
    #[Route('/modifier-son-cv/ajouter-une-formation', name: 'app_resume_course_add')]
    public function courseAdd(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $resume = $this->getOrCreateResume($user, $manager);

        $course = new Course();

        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $course = $form->getData();
            $course->setResume($resume);

            $manager->persist($course);
            $manager->flush();
        }

        return $this->redirectToRoute("app_resume_edit");
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/telecharger-son-cv', name: 'app_resume_download')]
    public function generatePdf(): Response
    {
        $data = "ARTHUR Félix";
        // Récupérer le contenu HTML du template bootstrap
        $html = $this->renderView('resume/template.html.twig', [
            'data' => $data,
        ]);

        // Générer le PDF avec FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',12);

        $pdf->writeHTML($html);
        // Récupérer le contenu du PDF en tant que chaîne de caractères
        $pdfContent = $pdf->Output('S');

        // Créer une réponse HTTP contenant le contenu du PDF
        $response = new Response($pdfContent);

        // Ajouter l'en-tête Content-Disposition pour forcer le téléchargement du PDF
        $response->headers->set('Content-Disposition', 'attachment; filename="mon-pdf.pdf"');

        return $response;
    }

}
