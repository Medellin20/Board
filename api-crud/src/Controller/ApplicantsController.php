<?php

namespace App\Controller;

use App\Entity\Applicants;
use App\Entity\Advertisements;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/applicants')]
class ApplicantsController extends AbstractController
{
    #[Route('/', name: 'app_applicants_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager=$doctrine->getManager();

        $applicants = $entityManager->getRepository(Applicant::class)->findAll();
        $advertisements = $entityManager->getRepository(Advertisements::class)->findAll();
        $data_ap=[];

        foreach($applicants as $applicant)
        {
            foreach($advertisements as $advertisement)
            {
                $data_ap[]=[
                    'id' => $applicant->getId(),
                    'name_ad' => $applicant->getNameAd(),
                    'firstname_ap' => $applicant->getFirstNameAp(),
                    'email_ap' =>$applicant->getEmailAp(),
                    'phone' =>$applicant->getPhone(),
                    'message' =>$applicant->getMessage(),
                    'advertisements' => $advertisement->getNameAd()
                ];
            }
        }

        return $this->json($data_ap);
    }

    #[Route('/new/{id}', name: 'app_applicants_new', methods: ['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager=$doctrine->getManager();

        $advertisement = $entityManager->getRepository(Advertisements::class)->find($id);

        $applicants = new Applicants();
        $te_ap = json_decode($request->getContent(), true);

        $applicants->addAdvertisement($advertisement);  
    
        $applicants->setNameAp($te_ap['name_ap']);
        $applicants->setFirstnameAp($te_ap['firstname_ap']);
        $applicants->setEmailAp($te_ap['email_ap']);
        $applicants->setPhone($te_ap['phone']);
        $applicants->setMessage($te_ap['message']);

        $entityManager->persist($applicants);
        $entityManager->persist($advertisement);
        $entityManager->flush();

        return $this->json('New applicant created with id '. $applicants->getId());
    }

    #[Route('/{id}', name: 'app_applicants_show', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $applicants = $doctrine->getRepository(Applicants::class)->find($id);

        if(!$applicants)
        {
            return $this->json('No applicants found for id ' . $id, 404);
        }

        $advertisements = $doctrine->getRepository(Advertisements::class)->findAll();
        //dump($advertisements);die;
        foreach($advertisements as $advertisement)
        {
            $data_ap[]=[
                'id' => $applicants->getId(),
                'name_ap' => $applicants->getNameAp(),
                'firstname_ap' => $applicants->getFirstNameAp(),
                'email_ap' =>$applicants->getEmailAp(),
                'phone' =>$applicants->getPhone(),
                'message' =>$applicants->getMessage(),
                'advertisements' => $advertisement->getNameAd()
            ];
        }

        return $this->json($data_ap);
    }

    #[Route('/{id}/edit', name: 'app_applicants_edit', methods: ['PUT', 'PATCH'])]
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $em = $doctrine->getManager();
        $applicants = $em->getRepository(Applicants::class)->find($id);

        if(!$applicants)
        {
            return $this->json('No appl$applicants found for id ' . $id, 404);
        }

        $content = json_decode($request->getContent());

        $applicants->setNameAp($content->name_ap);
        $applicants->setFirstNameAp($content->firstname_ap);
        $applicants->setEmailAp($content->email_ap);
        $applicants->setPhone($content->phone);
        $applicants->setMessage($content->message);

        $em->flush();

        $data_ap[]=[
            'id' => $applicants->getId(),
            'name_ap' => $applicants->getNameAp(),
            'firstname_ap' => $applicants->getFirstNameAp(),
            'email_ap' =>$applicants->getEmailAp(),
            'phone' =>$applicants->getPhone(),
            'message' =>$applicants->getMessage(),
        ];

        return $this->json($data_ap);
    }
    #[Route('/{id}', name: 'app_applicants_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $applicants = $em->getRepository(Applicants::class)->find($id);

        if(!$applicants)
        {
            return $this->json('No applicants found for id ' . $id, 404);
        }

        $em->remove($applicants);
        $em->flush();

        return $this->json('applicants was deleted for id ' . $id);
    }
}
