<?php
namespace Fpradas\TelegramBotBundle\Command;


use Fpradas\TelegramBotBundle\Bot;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListenerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fpradas:bot:listen')
            ->setDescription('as');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = '135257472:AAFvfs9GbQGN1mkuDwXIan9g-nE0y5xJEp4';
        $myBot = new Bot($token);
        $offset = null;

        $oBoobsClient = new \GuzzleHttp\Client([
            'base_uri' => 'http://api.oboobs.ru/',
        ]);

        $oButtsClient = new \GuzzleHttp\Client([
            'base_uri' => 'http://api.obutts.ru/',
        ]);

        $data = json_decode($oBoobsClient->get('/boobs/count/')->getBody(), true);
        $boobsCount = $data[0]['count'];

        $data = json_decode($oButtsClient->get('/butts/count/')->getBody(), true);
        $buttsCount = $data[0]['count'];

        do {
            $updates = $myBot->getUpdates($offset, 10);

            foreach ($updates as $update) {

                $message = $update->getMessage();

                if ($message->isFromGroup()) {


                    $user = $message->getFrom();
                    $name = "{$user->getFirstName()}";
                    $chatId = $message->getChat()->getId();
                    $output->writeln("Parsed message \"{$message->getText()}\" from {$user->getUsername()} <$chatId>");

                    $text = $message->getText();

                    if (preg_match("/^\/teta(?:s)?(?:\s)?(\d+)?.*/", $text, $matches)) {

                        if(isset($matches[1])) {
                            $n = (int) $matches[1];
                        } else {
                            $n = 1;
                        }

                        if($n > 9) {
                            $myBot->sendMessage($chatId, 'Salido!!!!!!! No tengo tantas tetas');
                        } else {
                            $output->writeln("{$user->getUsername()} <$chatId> wants $n boobs");
                            $start = mt_rand(0,$boobsCount - $n);

                            $boobs = json_decode($oBoobsClient->get("/boobs/$start/$n")->getBody(), true);

                            foreach($boobs as $data) {
                                $id = (string)$data['id'];
                                if (strlen($id) < 5) {
                                    $image = implode('', array_fill(0, 5 - strlen($id), 0)).$id;
                                } else {
                                    $image = $id;
                                }

                                $url = "http://media.oboobs.ru/boobs/$image.jpg";

                                try {
                                    file_put_contents("/tmp/boobs/$image.jpg", file_get_contents($url));
                                    $myBot->sendPhoto($chatId, "/tmp/boobs/$image.jpg", "Hola $name, mírame las tetas ;)");
                                } catch (\Exception $e) {
                                    $output->writeln("Error getting $url");
                                    continue;
                                }
                            }
                        }

                    } elseif (preg_match("/^\/culo(?:s)?(?:\s)?(\d+)?.*/", $text, $matches)) {

                        if(isset($matches[1])) {
                            $n = (int) $matches[1];
                        } else {
                            $n = 1;
                        }

                        if($n > 9) {
                            $myBot->sendMessage($chatId, 'Salido!!!!!!! No tengo tantos culos');
                        } else {
                            $output->writeln("{$user->getUsername()} <$chatId> wants $n butts");
                            $start = mt_rand(0,$buttsCount - $n);

                            $boobs = json_decode($oButtsClient->get("/butts/$start/$n")->getBody(), true);

                            foreach($boobs as $data) {
                                $id = (string)$data['id'];
                                if (strlen($id) < 5) {
                                    $image = implode('', array_fill(0, 5 - strlen($id), 0)).$id;
                                } else {
                                    $image = $id;
                                }

                                $url = "http://media.obutts.ru/butts/$image.jpg";

                                try {
                                    file_put_contents("/tmp/butts/$image.jpg", file_get_contents($url));
                                    $myBot->sendPhoto($chatId, "/tmp/butts/$image.jpg", "Hola $name, mírame el ojete ;)");
                                } catch (\Exception $e) {
                                    $output->writeln("Error getting $url");
                                    continue;
                                }
                            }
                        }

                    }elseif (preg_match("/^\/ruido(?:s)?(?:\s)?(\d+)?.*/", $text, $matches)) {

                        if(isset($matches[1])) {
                            $n = (int) $matches[1];
                        } else {
                            $n = 1;
                        }

                        if($n > 9) {
                            $myBot->sendMessage($chatId, 'Salido!!!!!!! No tengo tanto ruido');
                        } else {
                            $output->writeln("{$user->getUsername()} <$chatId> wants $n noise");

                            $sel = ['butts', 'boobs'];
                            $noise = $sel[array_rand($sel)];

                            $client = "o".ucfirst($noise)."Client";
                            $boobs = json_decode($$client->get("/noise/$n")->getBody(), true);

                            foreach($boobs as $data) {
                                $id = (string)$data['id'];
                                if (strlen($id) < 5) {
                                    $image = implode('', array_fill(0, 5 - strlen($id), 0)).$id;
                                } else {
                                    $image = $id;
                                }

                                $url = "http://media.o$noise.ru/noise/$image.jpg";

                                if(!is_dir("/tmp/$noise/noise/")) {
                                    mkdir("/tmp/$noise/noise/");
                                }
                                try {
                                    file_put_contents("/tmp/$noise/noise/$image.jpg", file_get_contents($url));
                                    $myBot->sendPhoto($chatId, "/tmp/$noise/noise/$image.jpg", "Hola $name, mírame el... que coño es noise (aparte de ruido) ;) [$noise]");
                                } catch (\Exception $e) {
                                    $output->writeln("Error getting $url");
                                    continue;
                                }
                            }
                        }

                    }

                  sleep(1);
                }

                $offset = (int)$update->getUpdateId() + 1;
            }

        } while (true);
    }
}