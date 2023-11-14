<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Applicants;
use App\Entity\Companies;
use App\Repository\UsersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'app_users_index', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager=$doctrine->getManager(); 

        $companies = $entityManager->getRepository(Companies::class)->findAll();
        $applicants = $entityManager->getRepository(Applicants::class)->findAll();
        $users = $entityManager->getRepository(Users::class)->findAll();

        $data_users=[];

        foreach($users as $user)
        {
            foreach($companies as $companie)
            {
                foreach($applicants as $applicant)
                {
                    $data_users=[
                        'id' => $user->getId(),
                        'companies' => $companie->getName(),
                        'applicants' => $applicant->getNameAp()
                    ];
                }
            }
        }

        return $this->json($data_users);
    }


    #[Route('/new/{idA}/{idC}', name: 'app_users_new', methods: ['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request, int $idA, int $idC): Response
    {
        $entityManager=$doctrine->getManager();

        $applicants = $entityManager->getRepository(Applicants::class)->find($idA);
        $companies = $entityManager->getRepository(Companies::class)->find($idC);

        $users = new Users();
        //$te_ad = json_decode($request->getContent(), true);
    
        $users->setCompanies($companies); 
        $users->setApplicants($applicants);

        $entityManager->persist($users);
        $entityManager->persist($applicants);
        $entityManager->persist($companies);
        $entityManager->flush();

        return $this->json('New user created with id '. $users->getId());
    }

    #[Route('/{id}', name: 'app_users_show', methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $users = $doctrine->getRepository(Users::class)->find($id);

        if(!$users)
        {
            return $this->json('No users found for id ' . $id, 404);
        }

        $companies = $doctrine->getRepository(Companies::class)->findAll();
        $applicants = $doctrine->getRepository(Applicants::class)->findAll();

        $data_users=[];

        foreach($companies as $companie)
            {
                foreach($applicants as $applicant)
                {
                    $data_users=[
                        'id' => $users->getId(),
                        'companies' => $companie->getName(),
                        'applicants' => $applicant->getNameAp()
                    ];
                }
            }

        return $this->json($data_users);
    }
    

    /*#[Route('/{id}/edit', name: 'app_users_edit', methods: ['PUT', 'PATCH'])]
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $em = $doctrine->getManager();
        $users = $em->getRepository(Users::class)->find($id);

        if(!$users)
        {
            return $this->json('No users found for id ' . $id, 404);
        }

        $content = json_decode($request->getContent());
        return $this->json($content);

        $users->setCompanies($content->companies);
        $users->setApplicants($content->applicants);

        $em->flush();

        $data = [
            'id' => $users->getId(),
            'companies' => $users->getCompanies(),
            'applicant' => $users->getApplicants()
        ];

        return $this->json($data);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $usersRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }*/
}
