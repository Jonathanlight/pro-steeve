<?php

namespace UserBundle\Service;

use FOS\UserBundle\Doctrine\UserManager;
use MailingBundle\Service\InlineStyles;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ToolsBundle\Service\FileTool;
use UserBundle\Entity\User;

class Import{

  private $fileTool;
  private $userManager;
  private $mailer;
  private $inlineStyles;
  private $companyParameters;
  private $twig;

  public function __construct(FileTool $fileTool, UserManager $userManager, \Swift_Mailer $mailer, \Twig_Environment $twig, InlineStyles $inlineStyles, $companyParameters) {
    $this->fileTool = $fileTool;
    $this->userManager = $userManager;
    $this->mailer = $mailer;
    $this->inlineStyles = $inlineStyles;
    $this->companyParameters = $companyParameters;
    $this->twig = $twig;
  }

  public function importUsers(UploadedFile $uploaded_file){
    $f = $this->fileTool->prepareUploadedFile($uploaded_file);

    if($f === false):
      return false;
    endif;

    ini_set('auto_detect_line_endings', true);

    $count = 0;
    $header = fgetcsv($f, 10000, ";");//First line contains header
    while (($data = fgetcsv($f, 10000, ";")) !== FALSE):
      $nom = iconv('macintosh', 'UTF-8', $data[0]);
      $prenom = iconv('macintosh', 'UTF-8', $data[1]);
      $email = iconv('macintosh', 'UTF-8', $data[2]);

      $userExist = $this->userManager->findUserByEmail($email);

      if($userExist === null && $email != "" && filter_var($email, FILTER_VALIDATE_EMAIL)):
        $user = new User();
        $user->setFirstName($prenom);
        $user->setLastName($nom);
        $user->setEmail($email);
        $user->setUsername($email);
        $user->setEnabled(TRUE);
        $user->addHierarchyRole("ROLE_STD_USER");
        $password = substr(bin2hex(random_bytes(80)),0,8);
        $user->setPlainPassword($password);
        $this->userManager->updateUser($user, TRUE); // Fait le flush

        $message = \Swift_Message::newInstance()
                                 ->setSubject('Votre compte STEEVE est disponible')
                                 ->setFrom(array($this->companyParameters['company_mail_sender'] => $this->companyParameters['company_name']))
                                 ->setTo($email)
                                 ->setBody($this->inlineStyles->insertStyles($this->twig->render('@User/Emails/new-account.html.twig', array('user' => $user, 'password' => $password))), 'text/html')
        ;
        $this->mailer->send($message);

        $count++;
      endif;

    endwhile;

    return $count===0?false:$count;
  }
}