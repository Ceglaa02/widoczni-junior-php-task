<?php

namespace App\Controller;

use App\Services\ClientsService;
use App\Services\ContactsService;
use App\Services\EmployeesService;
use App\Services\PackagesService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    private ClientsService $clientsService;
    private PackagesService $packagesService;
    private ContactsService $contactsService;
    private EmployeesService $employeesService;

    public function __construct(ClientsService $clientsService, PackagesService $packagesService, ContactsService $contactsService, EmployeesService $employeesService)
    {
        $this->clientsService = $clientsService;
        $this->packagesService = $packagesService;
        $this->contactsService = $contactsService;
        $this->employeesService = $employeesService;
    }

    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return $this->render('main.html.twig', [
            'clients' => $this->clientsService->getClients(),
            'contacts' => $this->contactsService->getContacts(),
            'packages' => $this->packagesService->getPackages(),
            'employees' => $this->employeesService->getEmployees(),
        ]);
    }
}