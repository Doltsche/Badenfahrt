<?php

namespace User\Service;

use Zend\Mail;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\Mail\Transport\SmtpOptions;

/**
 * Implements the UserMailServiceInterface interface.
 */
class UserMailService implements UserMailServiceInterface
{

    /**
     * @var array
     */
    protected $options;

    /**
     * Creates a new instance of the UserMailService class.
     * 
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = $options;
    }

    /**
     * Sends the given user a confirmation request email.
     * 
     * @param \User\Model\User $user
     */
    public function sendConfirmationRequest($user)
    {

        //Local Variables for easyer access
        $email = $user->getIdentity();      //Email from new User
        $ToLName = $user->getLastName();    //Users Last Name
        $ToFName = $user->getFirstName();   //Users First Name
        $sender = "info@127.0.0.1";

        //Predefinitions for HTML Mime Part
        //Setting Renderer for HTML Part
        $renderer = new PhpRenderer();
        $renderer->setResolver(new TemplateMapResolver(array(
            'confirmation-mail' => __DIR__ . '/../../../mail/confirmation-mail.phtml',
        )));

        //For sending local variables to HTML template
        $model = new ViewModel(array(
            'user' => $user,
            'sender' => $sender,
        ));

        //Setting Template
        $model->setTemplate('confirmation-mail');
        $content = $renderer->render($model);

        //Define as HTML
        $mhtml = new MimePart($content);
        $mhtml->type = "text/html; charset=UTF-8";

        // Text part
        $content = "Hallo, " . $ToFName . " " . $ToLName . ".\n\nVielen Dank für Ihre Anmeldung bei Badenfahrt.local.\n\n" . "Um Ihre Registrierung erfolgreich abzuschliessen, bitte den nachfolgenden Link besuchen: http://badenfahrt.local/user/confirm/" . $user->getToken() . "\n\n\tWebseite: HTTP://badenfahrt.local\n\tEmail: " . $sender;

        //Define as TXT
        $mtxt = new MimePart($content);
        $mtxt->type = "text/plain; charset=UTF-8";

        //Create a MIME-Email
        $body = new MimeMessage();

        //Set parts together as multipart MIME for HTML and TXT version
        $body->setParts(array($mhtml, $mtxt));

        // instance mail 
        $mail = new Mail\Message();
        $mail->setBody($body);

        //Set subject, recipent and sender
        $mail->setFrom($sender, 'Badenfahrt');
        $mail->addTo($email, $ToLName . " " . $ToFName);
        $mail->setSubject('Badenfahrt: Ihre Bestätigung wird benötigt für die Registrierung');

        $test = $this->options;

        //Send Email
        $options = new SmtpOptions($this->options['options']);
        $transport = new Mail\Transport\Smtp($options);
        $transport->send($mail);
    }

    /**
     * Sends the given user a password reset mail.
     * 
     * @param \User\Model\User $user $user
     */
    public function sendResetPassword($user)
    {
        // TODO
    }

}
