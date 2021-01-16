<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    #[Route('/{page<\d+>?1}', name: 'customer_index', methods: ['GET'])]
    public function index(
        Request $request,
        CustomerRepository $customerRepository,
        PaginatorInterface $paginator,
        int $page,
    ): Response
    {
        $filter = $request->get('search');

        $pagination = $paginator->paginate(
            $customerRepository->findPseudoSearch($filter),
            $page,
            10
        );

        $currentPageNumber = $pagination->getCurrentPageNumber();
        $numberOfPages = ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());

        return $this->render('customer/index/main.html.twig', [
            'customers' => $pagination,
            'currentPageNumber' => $currentPageNumber,
            'numberOfPages' => $numberOfPages,
        ]);
    }

    #[Route('/new', name: 'customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/history/{id}', name: 'customer_show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('customer/show/main.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Successfully uploaded');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'customer_delete', methods: ['DELETE'])]
    public function delete(Request $request, Customer $customer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customer_index');
    }
}
