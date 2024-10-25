<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Repository\AgenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud/agence')]
class CrudAgenceController extends AbstractController
{
    #[Route('/list', name: 'app_crud_agence')]
    public function listAgence(AgenceRepository $repository): Response
    {
        $agences = $repository->findAll();
        return $this->render('agence/listAgence.html', ['agences' => $agences]);
    }

    #[Route('/search', name: 'app_search_agence')]
    public function searchByName(Request $request, AgenceRepository $repository): Response
    {
        $name = $request->query->get('name');
        $agences = $repository->findBy(['name' => $name]);
        return $this->render('agence/listAgence.html', ['agences' => $agences]);
    }

    #[Route('/insert', name: 'app_insert_agence')]
    public function insertAgence(ManagerRegistry $doctrine): Response
    {
        $agence = new Agence();
        $agence->setName('Agence ');
        $agence->setLocal('Localisation ');

        $em = $doctrine->getManager();
        $em->persist($agence);
        $em->flush();

        return $this->redirectToRoute('app_crud_agence');
    }

    #[Route('/delete/{id}', name: 'app_delete_agence')]
    public function deleteAgence($id, AgenceRepository $repository, ManagerRegistry $doctrine): Response
    {
        $agence = $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($agence);
        $em->flush();

        return $this->redirectToRoute('app_crud_agence');
    }

    #[Route('/update/{id}', name: 'app_update_agence')]
    public function updateAgence(Agence $agence, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $agence->setLocal('Nouvelle Localisation');
        $em->flush();

        return $this->redirectToRoute('app_crud_agence');
    }
}
