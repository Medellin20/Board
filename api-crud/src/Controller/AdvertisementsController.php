<?php

namespace App\Controller;

use App\Entity\Advertisements;
use App\Entity\Companies;
use App\Entity\Applicants;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/advertisements')]
class AdvertisementsController extends AbstractController
{
    #[Route('/', name: 'app_advertisements_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager=$doctrine->getManager();

        $advertisements = $entityManager->getRepository(Advertisements::class)->findAll();
        $companies = $entityManager->getRepository(Companies::class)->findAll();
        $applicants = $entityManager->getRepository(Applicants::class)->findAll();
        $data_ad=[];

        foreach($advertisements as $advertisement)
        {
            foreach($companies as $companie)
            {
                $data_ad[]=[
                    'id' => $advertisement->getId(),
                    'name_ad' => $advertisement->getNameAd(),
                    'companies' => $companie->getName(),
                    'dateOfPublication' =>$advertisement->getDateOfPublication(),
                    'applicants' => $applicants->getNameAp(),
                ];
            }
        }

        return $this->json($data_ad);
    }

    #[Route('/new/{id}', name: 'app_advertisements_new', methods: ['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager=$doctrine->getManager();

        $company = $entityManager->getRepository(Companies::class)->find($id);
       // $applicant = $entityManager->getRepository(Applicants::class)->findAll();

        $advertisement = new Advertisements();
        $te_ad = json_decode($request->getContent(), true);

        $company->addAdvertisement($advertisement);  
        //$applicant->addApplicant($applicant);         
    
        $advertisement->setNameAd($te_ad['nameAd']);
        $advertisement->setCompanies($company); 
        $advertisement->setDateOfPublication($te_ad['dateOfPublication']);

        $entityManager->persist($advertisement);
        $entityManager->persist($company);
        //$entityManager->persist($applicant);
        $entityManager->flush();

        return $this->json('New advertisements created with id '. $advertisement->getId());
    }

    #[Route('/{id}', name: 'app_advertisements_show', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $advertisement = $doctrine->getRepository(Advertisements::class)->find($id);

        if(!$advertisement)
        {
            return $this->json('No advertisements found for id ' . $id, 404);
        }

        //$applicants = $doctrine->getRepository(Applicants::class)->findBy(['advertisements' => $advertisement ]);
        $company = $doctrine->getRepository(Companies::class)->findAll();
        //dump($company);die;
        foreach($company as $companies)
        {
            //foreach($applicants as $applicant)
            //{
                $data = [
                    'id' => $advertisement->getId(),
                    'nameAd' => $advertisement->getNameAd(),
                    'Companies' => $companies->getName(),
                    'dateOfPublication' => $advertisement->getDateOfPublication(),
                    //'applicants' => $applicant->getNameAp(),
                ];
           // }
        }

        return $this->json($data);
    }

    #[Route('/{id}/edit', name: 'app_advertisements_edit', methods: ['PUT', 'PATCH'])]
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $em = $doctrine->getManager();
        $advertisements = $em->getRepository(Advertisements::class)->find($id);

        if(!$advertisements)
        {
            return $this->json('No advertisements found for id ' . $id, 404);
        }

        $content = json_decode($request->getContent());

        $advertisements->setNameAd($content->nameAd);
        $advertisements->setDateOfPublication($content->dateOfPublication);

        $em->flush();

        $data = [
            'id' => $advertisements->getId(),
            'nameAd' => $advertisements->getNameAd(),
            'DateOfPublication' => $advertisements->getDateOfPublication()
        ];

        return $this->json($data);
    }

    #[Route('/{id}', name: 'app_advertisements_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $advertisements = $em->getRepository(Advertisements::class)->find($id);

        if(!$advertisements)
        {
            return $this->json('No advertisements found for id ' . $id, 404);
        }

        $em->remove($advertisements);
        $em->flush();

        return $this->json('Advertisements was deleted for id ' . $id);
    }
}
