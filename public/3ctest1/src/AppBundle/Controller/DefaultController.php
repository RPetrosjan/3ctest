<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Burger;
use AppBundle\Form\BurgerForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class DefaultController
 *
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{

    /** @var CsrfTokenManagerInterface */
    private $tokenManager;

    /** @var EntityManagerInterface  */
    private $em;

    public function __construct(CsrfTokenManagerInterface $tokenManager,  EntityManagerInterface $em) {
        $this->tokenManager = $tokenManager;
        $this->em = $em;
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request) {

        $burgerArray = $this->em->getRepository(Burger::class)->getBurgerId(false);
        if(!empty($burgerArray)) {
            $burgerArray = current($burgerArray);
            // convert x => xx.xx
            $burgerArray['price'] = str_pad(number_format($burgerArray['price'],'2','.',''), 5, "0", STR_PAD_LEFT).' &euro;';
            $burgerArray['supp_double'] = str_pad(number_format($burgerArray['supp_double'],'2','.',''), 5, "0", STR_PAD_LEFT);
        }
        // replace this example code with whatever you need
        return $this->render('@App/page/homepage.html.twig', [
            'burger' => $burgerArray,
        ]);
    }

    /**
     * @Route("/shwoburger/{id}/{direction}", name="shwoburger", requirements={"\d+"}, options = { "expose" = true }, condition="request.isXmlHttpRequest()")
     * @param Request $request
     * @param null $id
     * @param null $direction
     * @return JsonResponse|RedirectResponse|Response
     */
    public function showBurger(Request $request, $id = null, $direction = null){

        if(!is_null($id)) {
            $burgerArray = $this->em->getRepository(Burger::class)->getBurgerId($id, $direction);
            if(!empty($burgerArray)) {
                $burgerArray = current($burgerArray);
                // convert x => xx.xx
                $burgerArray['price'] = str_pad(number_format($burgerArray['price'],'2','.',''), 5, "0", STR_PAD_LEFT);
                $burgerArray['supp_double'] = str_pad(number_format($burgerArray['supp_double'],'2','.',''), 5, "0", STR_PAD_LEFT);
            }
            return new JsonResponse([
                'burger' => $burgerArray
            ], 200);
        }
        else {
            return new JsonResponse([
                'result' => 'nout found'
            ], 500);
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/removeBurger/{id}", name="removeburger", requirements={"\d+"})
     * @param Request $request
     * @param null $id
     * @return RedirectResponse
     */
    public function removeBurgerPage(Request $request, $id = null) {
        if(!is_null($id)) {
            $burgerEntity = $this->em->getRepository(Burger::class)->find($id);
            if(!is_null($burgerEntity)) {
                $this->em->remove($burgerEntity);
                $this->em->flush();
            }
        }
        return $this->redirectToRoute('adminPage');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/updateBurger/{id}", name="updateformburger", requirements={"\d+"})
     * @param Request $request
     * @param null $id
     * @return RedirectResponse
     */
    public function updateBurgerPage(Request $request, $id = null) {
        if(!is_null($id)) {
            $burgerEntity = $this->em->getRepository(Burger::class)->find($id);
            if(!is_null($burgerEntity)) {
                $burgerForm = $this->createForm(BurgerForm::class, $burgerEntity);
                $burgerForm->handleRequest($request);
                if($burgerForm->isSubmitted() && $burgerForm->isValid()) {
                    /** @var Burger $burgerEntity */
                    $burgerEntity = $burgerForm->getData();
                    $this->em->persist($burgerEntity);
                    $this->em->flush();
                }
            }
        }
        return $this->redirectToRoute('adminPage');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/getformburger/{id}", name="formburger", requirements={"\d+"}, options = { "expose" = true }, condition="request.isXmlHttpRequest()")
     * @param Request $request
     * @param null $id
     * @return JsonResponse|RedirectResponse|Response
     */
    public function getformburgerPage(Request $request, $id = null) {
        /** @var Burger $burgerEntity */
        $burgerEntity = $this->em->getRepository(Burger::class)->find($id);
        if(is_null($burgerEntity)) {
            return new JsonResponse([
               'form' => 'nout found'
            ], 500);
        }
        else {
            $burgerForm = $this->createForm(BurgerForm::class, $burgerEntity, [
                'action' => $this->generateUrl('updateformburger',[
                    'id' => $id
                ])
            ]);
            return $this->render('@App/form/burgerForm.html.twig', [
                'burgerForm' => $burgerForm->createView(),
            ]);
        }
    }

    /**
     * @Route("/admin", name="adminPage")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function adminPage(Request $request) {

        /** @var null|Form $burgerForm */
        $burgerForm = null;
        /** @var null|array $allBurgers */
        $allBurgers = null;

        // Check if user have Permission to see page
        if ($this->isGranted('ROLE_ADMIN')) {
            // Creating new Form for adding Burger
            /** @var Burger $burgerEntity */
            $burgerEntity = new Burger();
            $burgerForm = $this->createForm(BurgerForm::class, $burgerEntity);

            $burgerForm->handleRequest($request);
            if($burgerForm->isSubmitted() && $burgerForm->isValid()) {
                /** @var Burger $burgerEntity */
                $burgerEntity = $burgerForm->getData();
                $this->em->persist($burgerEntity);
                $this->em->flush();
                return $this->redirectToRoute('adminPage');
            }

            // Get all Burger arrray list
            $allBurgers = $this->em->getRepository(Burger::class)->getAllBurger();

        }

        /** @var $session Session */
        $session = $request->getSession();

        $authErrorKey = \Symfony\Component\Security\Core\Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = \Symfony\Component\Security\Core\Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (NULL !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = NULL;
        }

        if (!$error instanceof AuthenticationException) {
            $error = NULL; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (NULL === $session) ? '' : $session->get(
            $lastUsernameKey
        );

        $csrfToken = $this->tokenManager
            ? $this->tokenManager->getToken('authenticate')->getValue()
            : NULL;

        // replace this example code with whatever you need
        return $this->render(
            '@App/admin/admin.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
                'burgerForm' => $burgerForm == null ? null:$burgerForm->createView(),
                'allBurgers' => $allBurgers,
            ]
        );
    }

}
