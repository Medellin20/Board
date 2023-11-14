<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Entity\Companies;
use App\Entity\Applicants;
use App\Entity\Advertisements;
use App\Repository\JobsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jobs')]
class JobsController extends AbstractController
{
    #[Route('/', name: 'app_jobs_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager=$doctrine->getManager();

        $advertisements = $entityManager->getRepository(Advertisements::class)->findAll();
        $companies = $entityManager->getRepository(Companies::class)->findAll();
        $jobs = $entityManager->getRepository(Jobs::class)->findAll();
        $applicants = $entityManager->getRepository(Applicants::class)->findAll();
        $data_ad=[];

        foreach($jobs as $job)
        {
            foreach($companies as $companie)
            {
                foreach($advertisements as $advertisement)
                {
                    foreach($applicants as $applicant)
                    {
                        $data_jo[]=[
                            'id' => $job->getId(),
                            'name' => $job->getName(),
                            'advertisements' => $advertisement->getNameAd(),
                            'companies' => $companie->getName(),
                            'applicants' => $applicant->getNameAp(),
                            'email_send_companies' =>$job->getEmailSendCompanies(),
                            'email_send_companies' =>$job->getEmailSendCompanies()
                        ];
                    }
                }
            }
        }

        return $this->json($data_ad);
    }

    #[Route('/new/{id}', name: 'app_jobs_new', methods: ['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager=$doctrine->getManager();

        $company = $entityManager->getRepository(Companies::class)->find($id);
        $advertisements = $entityManager->getRepository(Advertisements::class)->findAll();

        $jobs = new Jobs();
        $te_jo = json_decode($request->getContent(), true);   
    
        $jobs->setName($te_jo['name']);
        $jobs->setEmailSentApply($te_jo['email_sent_apply']);
        $jobs->setEmailSentCompanies($te_jo['email_sent_companies']);
        $jobs->setCompanies($company); 
        $jobs->setAdvertisements($advertisements);

        $entityManager->persist($jobs);
        $entityManager->persist($company);
        $entityManager->persist($advertisements);
        $entityManager->flush();

        return $this->json('New jobs created with id '. $jobs->getId());
    }

    #[Route('/{id}', name: 'app_jobs_show', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $jobs = $doctrine->getRepository(Jobs::class)->find($id);

        if(!$jobs)
        {
            return $this->json('No jobs found for id ' . $id, 404);
        }

        $company = $doctrine->getRepository(Companies::class)->findAll();
        $advertisements = $doctrine->getRepository(Advertisements::class)->findAll();
        //dump($company);die;
        foreach($company as $companies)
        {
            foreach($advertisements as $advertisement)
            {
                $data = [
                    'id' => $jobs->getId(),
                    'name' => $jobs->getName(),
                    'Companies' => $companies->getName(),
                    'Advertisements' => $advertisement->getNameAd(),
                    'email_sent_apply' => $jobs->getEmailSentApply(),
                    'email_sent_companies' => $jobs->getEmailSentCompanies()
                ];
           }
        }

        return $this->json($data);
    }

    #[Route('/{id}/edit', name: 'app_jobs_edit', methods: ['PUT', 'PATCH'])]
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $em = $doctrine->getManager();
        $jobs = $em->getRepository(Jobs::class)->find($id);

        if(!$jobs)
        {
            return $this->json('No jobs found for id ' . $id, 404);
        }

        $content = json_decode($request->getContent());

        $jobs->setName($content->name);
        $jobs->setEmailSentApply($content->email_sent_apply);
        $jobs->setEmailSentCompanies($content->email_sent_companies);

        $em->flush();

        $data = [
            'id' => $jobs->getId(),
            'name' => $jobs->getName(),
            'email_send_companies' =>$jobs->getEmailSendCompanies(),
            'email_send_companies' =>$jobs->getEmailSendCompanies()
        ];

        return $this->json($data);
    }

    #[Route('/{id}', name: 'app_jobs_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $jobs = $em->getRepository(Jobs::class)->find($id);

        if(!$jobs)
        {
            return $this->json('No jobs found for id ' . $id, 404);
        }

        $em->remove($jobs);
        $em->flush();

        return $this->json('an jobs was deleted for id ' . $id);
    }
}
