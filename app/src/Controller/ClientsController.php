<?php

namespace App\Controller;

use App\Services\ClientsService;
use App\Services\ContactsService;
use App\Services\EmployeesService;
use App\Services\PackagesService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ClientsController extends AbstractController
{
    private PackagesService $packagesService;
    private EmployeesService $employeesService;

    public function __construct(PackagesService $packagesService, EmployeesService $employeesService)
    {
        $this->packagesService = $packagesService;
        $this->employeesService = $employeesService;
    }

    #[Route('/clients/add', name: 'client-add')]
    public function index(): Response
    {
        return $this->render('clients.html.twig', [
            'packages' => $this->packagesService->getPackages(),
            'employees' => $this->employeesService->getEmployees()
        ]);
    }

    #[Route('/clients/add/save', name: 'client-add-save', methods: ['POST'])]
    public function add(Request $request, ClientsService $clientsService): RedirectResponse
    {
        $contact = $request->get('contact');
        $client = $request->get('client');

        $result = false;

        if (!(!is_array($contact) || !is_array($client))) {
            $result = $clientsService->add($contact, $client);
        }

        if ($result) {
            return $this->redirect('/');
        } else {
            return $this->redirect('/clients/add');
        }
    }

}