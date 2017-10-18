public function registerAction(Request $request)
{

$user = new User();
if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
{
$userName = $this->container->get('security.token_storage')->getToken()->getUser()->getName();
}
else{
$userName = "Administrateur de site de l'Osel";
}

if ($this->get('security.authorization_checker')->isGranted('ROLE_WEBMASTER'))
{
$form = $this->get('form.factory')->create(AdminUserType::class, $user);

if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
{
try {
$pwd= $this->get('security.encoder_factory')->getEncoder($user)->encodePassword(md5(uniqid(null, true)), $user->getSalt());

$user->defineRest($pwd);
//on crée une entrée temporaire qui permettera au membre de changer son mot de passe
if($user->getActif())
{
$temp = new Temp();
$temp->setRole("setpwd");
$temp->setValidity("172800");
$temp->setActive(true);
$user->addTemp($temp);
$temp->setUser($user);
}


$em = $this->getDoctrine()->getManager();
$em->persist($user);

$em->flush();
}
catch (Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
$request->getSession()->getFlashBag()->add('Error', 'Ce membre est déjà inscrit!');
}

if($user->getActif())
{
$mail = $this->container->get('OSEL_User.registerMail');

if ($mail->sendRegisterMail($user->getName(), $user->getId(), $temp->getSha(), $userName, $user->getEmail(), $user->getName().$user->getLastname())) {
$request->getSession()->getFlashBag()->add('notice', 'Un mail a été envoyé au nouveau membre');
} else {
$request->getSession()->getFlashBag()->add('Error', 'Le mail n\'a pas pu être envoyé au nouveau membre: contactez le webmaster');

}
}



$request->getSession()->getFlashBag()->add('notice', 'Un nouveau membre à bien été rajouté');

return $this->redirect($this->generateUrl('register'));
}
}
elseif ($this->get('security.authorization_checker')->isGranted('ROLE_SECRETAIRE'))
{
$form = $this->get('form.factory')->create(UserType::class, $user);

if ($form->handleRequest($request)->isValid())
{
$role = new Roles();
$role = $this->getDoctrine()->getManager()->getRepository('OSELUserBundle:Roles')->findOneBy(array('role' => 'USER_ROLE'));
$user->addUserRole($role);
$role->setUser($user);
$pwd= $this->get('security.encoder_factory')->getEncoder($user)->encodePassword(md5(uniqid(null, true)), $user->getSalt());

$user->defineRest($pwd);
if($user->getActif())
{
$temp = new Temp();
$temp->setRole("setpwd");
$temp->setValidity("172800");
$temp->setActive(true);
$user->addTemp($temp);
$temp->setUser($user);
}

$em = $this->getDoctrine()->getManager();
$em->persist($user);
$em->flush();

$mail = $this->container->get('OSEL_User.registerMail');

if($user->getActif())
{
if ($mail->sendRegisterMail($user->getName(), $user->getId(), $temp->getSha(), $userName, $user->getName().$user->getLastname(), $user->getEmail()))
{
$request->getSession()->getFlashBag()->add('notice', 'Un mail a été envoyé au nouveau membre');
}
else
{
$request->getSession()->getFlashBag()->add('Error', 'Le mail n\'a pas pu être envoyé au nouveau membre: contactez le webmaster');

}
}




$request->getSession()->getFlashBag()->add('notice', 'Un nouveau membre à bien été rajouté');

return $this->redirect($this->generateUrl('register'));
}
}
else
{
$request->getSession()->getFlashBag()->add('ERROR', 'Vous n\'avez pas droit d\'exécuter cette action');
return $this->redirect($this->generateUrl('osel_core_home'));
}

return $this->render('OSELUserBundle:User:add.html.twig', array(
'form' => $form->createView(),
'selectedPage'	=> 'membres'
));
}