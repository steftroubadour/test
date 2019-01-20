<?php
// src/AppBundle/Command/CreateUserCommand.php
namespace AppBundle\Command;

use AppBundle\Entity\Basket;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteCommand extends ContainerAwareCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:execute';

    private $mailer;
    private $doctrine;
    private $twig;

    public function __construct(
        \Swift_Mailer $mailer,
        \Symfony\Bridge\Doctrine\RegistryInterface $doctrine,
        \Twig\Environment $twig
        )
    {
        $this->mailer = $mailer;
        $this->doctrine = $doctrine;
        $this->twig = $twig;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Notification par email des paniers abandonnés à J+1')
            ->setHelp('la description suffit!')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $baskets = $this->doctrine
            ->getRepository("AppBundle:Basket")
            ->getAbandonedBasketsDayBefore()
        ;

        $print = 'Un email a été envoyé a ' . count($baskets) . ' personne.s : ';
        /** @var Basket $basket */
        foreach ( $baskets as $basket ) {
            $message = \Swift_Message::newInstance('Votre panier est plein!')
                ->setFrom(array( $this->getContainer()->getParameter('mailer_user') => 'Mr Seller'))
                ->setTo(array( $basket->getUser()->getEmail() => $basket->getUser()->getUsername()))
                ->setBody(
                    $this->twig->render(
                        'email/abandonedBasketDayAfter.html.twig',
                        ['basket' => $basket]
                    ),
                    'text/html'
                )
            ;

            $this->mailer->send($message);
            $print = $print . $basket->getUser()->getEmail() . ', ';
        }

        $output->write($print);
    }
}