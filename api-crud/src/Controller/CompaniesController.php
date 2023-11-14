<?php

namespace App\Controller;

use App\Entity\Advertisements;
use App\Entity\Companies;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/companies')]
class CompaniesController extends AbstractController
{
    #[Route('/', name: 'app_companies_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $emplois = $doctrine->getRepository(Companies::class)->findAll();
        $advertisements=$doctrine->getRepository(Advertisements::class)->findAll();
        $data =[];
        
        foreach($emplois as $emploi)
        {
            foreach($advertisements as $advertisement)
            {
                $data[]=[
                    'id' => $emploi->getId(),
                    'name' => $emploi->getName(),
                    'localisation' => $emploi->getLocalisation(),
                    'email' => $emploi->getEmail(),
                    'advertisements' => $advertisement->getNameAd()
                ];
            }
        }

        return $this->json($data);
    }

    #[Route('/new', name: 'app_companies_new', methods: ['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager=$doctrine->getManager();

        $company=new Companies();
        $te = json_decode($request->getContent(), true);
        $company->setName($te['name']);
        $company->setLocalisation($te['localisation']); 
        $company->setEmail($te['email']);

        $entityManager->persist($company);
        $entityManager->flush();

        return $this->json('New companies created with id '. $company->getId());
    }

    #[Route('/{id}', name: 'app_companies_show', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $company = $doctrine->getRepository(Companies::class)->find($id);

        if(!$company)
        {
            return $this->json('No companies found for id ' . $id, 404);
        }

        $advertisements = $doctrine->getRepository(Advertisements::class)->findBy(['companies' => $company ]);
        //dump($advertisements);die;

       foreach($advertisements as $advertisement)
       {
        $data = [
            'id' => $company->getId(),
            'name' => $company->getName(),
            'localisation' => $company->getLocalisation(),
            'email' => $company->getEmail(),
            'advertisements' => $advertisement->getNameAd()
        ];
       }

        return $this->json($data);
    }

    #[Route('/{id}/edit', name: 'app_companies_edit', methods: ['PUT', 'PATCH'])]
    public function edit(ManagerRegistry $doctrine, Request $request, Companies $company, int $id): Response
    {
        $em = $doctrine->getManager();
        $company = $em->getRepository(Companies::class)->find($id);

        if(!$company)
        {
            return $this->json('No companies found for id ' . $id, 404);
        }

        $content = json_decode($request->getContent());

        $company->setName($content->name);
        $company->setLocalisation($content->localisation);
        $company->setEmail($content->email);

        $em->flush();

        $data = [
            'id' => $company->getId(),
            'name' => $company->getName(),
            'localisation' => $company->getLocalisation(),
            'email' => $company->getEmail(),
        ];

        return $this->json($data);
    }

    #[Route('/{id}', name: 'app_companies_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, Companies $company, int $id): Response
    {
        $em = $doctrine->getManager();
        $company = $em->getRepository(Companies::class)->find($id);

        if(!$company)
        {
            return $this->json('No companies found for id ' . $id, 404);
        }

        $em->remove($company);
        $em->flush();

        return $this->json('Companies was deleted for id ' . $id);
    }
}
