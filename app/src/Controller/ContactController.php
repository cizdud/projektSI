<?php
/**
 * Contact controller.
 */

namespace App\Controller;

use App\Entity\Contact;
use App\Form\Type\ContactType;
use App\Service\ContactServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ContactController.
 *
 * Manages the CRUD operations for Contact entities.
 */
#[Route('/contact')]
class ContactController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param ContactServiceInterface $contactService Contact service
     * @param TranslatorInterface     $translator     Translator
     */
    public function __construct(private readonly ContactServiceInterface $contactService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Index action.
     *
     * Displays a paginated list of contacts.
     *
     * @param int $page Page number for pagination
     *
     * @return Response HTTP response
     */
    #[Route(name: 'contact_index', methods: 'GET')]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->contactService->getPaginatedList($page);

        return $this->render('contact/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * Displays a single contact entity.
     *
     * @param Contact $contact Contact entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'contact_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', ['contact' => $contact]);
    }

    /**
     * Create action.
     *
     * Creates a new contact entity.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'contact_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactService->save($contact);

            $this->addFlash('success', $this->translator->trans('message.created_successfully'));

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit action.
     *
     * Edits an existing contact entity.
     *
     * @param Request $request HTTP request
     * @param Contact $contact Contact entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'contact_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactType::class, $contact, [
            'method' => 'PUT',
            'action' => $this->generateUrl('contact_edit', ['id' => $contact->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactService->save($contact);

            $this->addFlash('success', $this->translator->trans('message.edited_successfully'));

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/edit.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }

    /**
     * Delete action.
     *
     * Deletes a contact entity.
     *
     * @param Request $request HTTP request
     * @param Contact $contact Contact entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'contact_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Contact $contact): Response
    {
        $form = $this->createForm(FormType::class, $contact, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('contact_delete', ['id' => $contact->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactService->delete($contact);

            $this->addFlash('success', $this->translator->trans('message.deleted_successfully'));

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/delete.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }
}
