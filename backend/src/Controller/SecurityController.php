<?php
namespace App\Controller;

use Transliterator;
use App\Entity\User;
use App\Helper\AuthToken;
use App\Form\CreateUserType;
use App\Helper\FormValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends BaseController
{
    /**
     * @Route("/test", name="app_test", methods={"GET"})
     */
    public function test()
    {
        $string = "Jalapeños";
        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', Transliterator::FORWARD);
        $normalized = $transliterator->transliterate($string);

        $filteredString = preg_replace("/[^A-Za-z0-9 \,\'\"\;\-\_\%\$\:\?\.\+\*\&\!\@\#\(\)]/", '', $normalized);

        return $this->json(['test' => $filteredString]);
    }

    /**
     * @Route("/user/login", name="app_login", methods={"POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->json([
            'result' => false,
            'message' => $error,
            'last_username' => $lastUsername
        ]);
    }

    /**
     * @Route("/user/register", name="app_user_register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $form = $this->createForm(CreateUserType::class, $user);
        $form->submit(json_decode($request->getContent(), true));

        FormValidator::validate($form);


        $password = $form->get("password")->getData();
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return $this->json([
            'result' => true
        ]);
    }

    /**
     * @Route("/user/auth", name="app_auth_token", methods={"GET"})
     */
    public function getAuthToken()
    {
        $token = AuthToken::generate();
        return $this->json([
            'token' => $token,
            'user' => $this->getUser() ? [
                'id' => $this->getUser()->getId(),
                'email' => $this->getUser()->getEmail()
            ] : null
        ]);
    }
}
