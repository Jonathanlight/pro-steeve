<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\ImportType;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use UserBundle\Form\UserType;

/**
 * Stduser controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
  /**
   * Lists all User entities.
   *
   * @Route("/list", name="userList")
   * @Method("GET")
   */
  public function listAction()
  {
    $em = $this->getDoctrine()->getManager();

    $users = $em->getRepository('UserBundle:User')->findBy(array(), array('id' => 'ASC'));

    return $this->render('UserBundle:User:list.html.twig', array(
      'users' => $users,
    ));
  }

  /**
   * Import Users.
   *
   * @Route("/import", name="userImport")
   * @Method({"GET", "POST"})
   */
  public function importAction(Request $request)
  {
    $user = new User();
    $form = $this->createForm(ImportType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $uploadedFile = $form['fichier']->getData();

      if($uploadedFile->getClientOriginalExtension() !== "csv"):
        $form->get('fichier')->addError(new FormError('Vous devez fournir un fichier CSV.'));
      else:
        $countImport = $this->get('user.import')->importUsers($uploadedFile);

        if($countImport === false):
          $this->addFlash('danger', 'Une erreur est survenue, aucun utilisateur n\'a pu être créé.');
          return $this->redirectToRoute( 'userImport' );
        endif;

        $this->addFlash('success', '<i class="fa fa-plus"></i> '. '<strong>'.$countImport.' utilisateurs</strong> ont été créés.');
        return $this->redirectToRoute( 'userList' );
      endif;
    }

    return $this->render('UserBundle:User:import.html.twig', array(
      'user' => $user,
      'title' => 'Importation des utilisateurs',
      'subtitle' => '',
      'form' => $form->createView(),
    ));
  }

  /**
   * Finds and displays a User entity.
   *
   * @Route("/{id}/show", name="userShow")
   * @Method("GET")
   */
  public function showAction(User $user)
  {
    return $this->render('UserBundle:User:show.html.twig', array(
      'user' => $user,
      'title' => 'Détail d\'un utilisateur',
    ));
  }

  /**
   * Creates a new User entity.
   *
   * @Route("/add", name="userAdd")
   * @Method({"GET", "POST"})
   */
  public function addAction(Request $request)
  {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $userManager = $this->get('fos_user.user_manager');
      $userExist = $userManager->findUserByEmail($user->getEmail());
      $formEmail = $request->request->get('user')['email'];
      $formPassword = $request->request->get('user')['password']['first'];

      if($userExist !== null) {
        $form->get('email')->addError(new FormError('Adresse e-mail déjà utilisée.'));
      } else if($formEmail != "" && !filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {
        $form->get('email')->addError(new FormError('L\'adresse e-mail'. ' "' . $formEmail . '" ' . 'n\'est pas une adresse e-mail valide.'));
      } else if(strlen($formPassword) < 6){
        $form->get('password')->addError(new FormError('Le mot de passe doit faire au minimum 6 caractères.'));
      } else {
        $user->setEnabled( true );
        $user->addHierarchyRole($request->request->get('user')['role']);

        if ( $user->getPassword() != '' ) {
          $user->setPlainPassword( $user->getPassword() );
          $userManager->updateUser( $user, true ); // Fait le flush
        }

        $this->addFlash('success', '<i class="fa fa-plus"></i> '. 'L\'utilisateur ' .$user->getLastName().' '.$user->getFirstName().'</strong> ' . 'a été créé.');
        return $this->redirectToRoute( 'userList' );
      }
    }

    return $this->render('UserBundle:User:add.html.twig', array(
      'user' => $user,
      'title' => 'Nouvel utilisateur',
      'subtitle' => 'Ajouter un utilisateur',
      'form' => $form->createView(),
    ));
  }

  /**
   * Displays a form to edit an existing User entity.
   *
   * @Route("/{id}/edit", name="userEdit")
   * @Method({"GET", "POST"})
   */
  public function editAction(Request $request, User $user)
  {
    $user->setEmail($user->getEmail());//Default value for form because User email is not ORM
    $user->setUsername($user->getUsername()); //Default value for form because User username is not ORM

    $deleteForm = $this->createDeleteForm($user);
    $editForm = $this->createForm(UserType::class, $user, array('passwordRequired' => false));
    $editForm->handleRequest($request);

    if ($editForm->isSubmitted() && $editForm->isValid()) {

      $userManager = $this->get('fos_user.user_manager');
      $userExist = $userManager->findUserByEmail($request->get('user')->getEmail());

      $userEmail = $user->getEmail();//My email
      $userEmailForm = $request->get('user')->getEmail();//My new email
      $formEmail = $request->request->get('user')['email'];

      if($userExist !== null && $userEmail != $userEmailForm) {
        $editForm->get('email')->addError(new FormError('Adresse e-mail déjà utilisée.'));
      } else if($formEmail != "" && !filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {
        $editForm->get('email')->addError(new FormError('L\'adresse e-mail'. ' "' . $formEmail . '" ' . 'n\'est pas une adresse e-mail valide.'));
      } else {
        $user->addHierarchyRole($request->request->get('user')['role']);

        if ( $user->getPassword() != '' ) {
          $user->setPlainPassword( $user->getPassword() );
          $userManager->updateUser( $user, true );//Le flush n'est plus necessaire ici
        }

        $this->addFlash('success', '<i class="fa fa-pencil-square-o"></i> '. 'L\'utilisateur ' .$user->getLastName().' '.$user->getFirstName().'</strong> ' . 'a été modifié.');
        return $this->redirectToRoute( 'userList' );
      }
    }

    return $this->render('UserBundle:User:edit.html.twig', array(
      'user' => $user,
      'title' => 'Edition d\'un utilisateur',
      'subtitle' => 'Editez un utilisateur',
      'form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    ));
  }

  /**
   * Displays a form to confirm delete an existing User entity.
   *
   * @Route("/{id}/confirm", name="userConfirmDelete")
   * @Method({"GET", "POST"})
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function confirmDeleteAction(User $user)
  {
    $deleteForm = $this->createDeleteForm($user);

    return $this->render('UserBundle:User:delete.html.twig', array(
      'user' => $user,
      'title' => 'Confirmer la suppression d\'un utilisateur',
      'subtitle' => 'Ceci entrainera la suppression définitive de l\'utilisateur',
      'delete_form' => $deleteForm->createView(),
    ));
  }

  /**
   * Deletes a User entity.
   *
   * @Route("/{id}/delete", name="userDelete")
   * @Method("DELETE")
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function deleteAction(Request $request, User $user)
  {
    $form = $this->createDeleteForm($user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($user);
      $em->flush();
      $this->addFlash('success', '<i class="fa fa-trash"></i> '. 'L\'utilisateur '.$user->getLastName().' '.$user->getFirstName().'</strong> ' . 'a été supprimé.');
    }

    return $this->redirectToRoute('userList');
  }

  /**
   * Creates a form to delete a User entity.
   *
   * @param User $user The User entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createDeleteForm(User $user)
  {
    return $this->createFormBuilder()
                ->setAction($this->generateUrl('userDelete', array('id' => $user->getId())))
                ->setMethod('DELETE')
                ->getForm();
  }
}
