<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 21/01/2015
 * Time: 11:13
 */
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;

class CommandeServeur extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('chat:server')
            ->setDescription('Lance le serveur de chat');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $chat = $this->getContainer()->get('chat');
        $server = IoServer::factory($chat, 8080);
        $server->run();
    }
} 