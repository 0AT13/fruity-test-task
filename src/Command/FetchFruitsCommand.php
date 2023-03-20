<?php

namespace App\Command;

use App\Entity\Family;
use App\Entity\Fruit;
use App\Entity\Genus;
use App\Entity\Order;
use App\Helpers\CheckIfExist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCommand(
    name: 'app:fetch-fruits',
    description: 'Fetches the fruits from https://fruityvice.com/ and write them into db',
)]
class FetchFruitsCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private TranslatorInterface $translator,
        #[Autowire('%admin_email%')] private $adminEmail
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('fruit', InputArgument::OPTIONAL, 'What fruit to fetch', 'all');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fruit = $input->getArgument('fruit');

        $response = $this->client->request(
            'GET',
            'https://fruityvice.com/api/fruit/' . $fruit
        );

        if ($response->getStatusCode() === Response::HTTP_OK) {
            $data = $response->toArray();

            if (!isset($data[0])) {
                if ($fruit = self::saveFruit($data)) {
                    $this->mailer->send((new NotificationEmail())
                            ->subject($this->translator->trans('emails.fruits.added.subject'))
                            ->htmlTemplate('emails/fruit_added_notification.html.twig')
                            ->from($this->adminEmail)
                            ->to($this->adminEmail)
                            ->context(['fruit' => $fruit])
                    );
                };
            } else {
                $fruits = [];
                foreach ($data as $item) {
                    if ($fruit = self::saveFruit($item)) {
                        $fruits[] = $fruit;
                    };
                }

                if (!empty($fruits)) {
                    $this->mailer->send((new NotificationEmail())
                            ->subject($this->translator->trans('emails.fruits.multiple_added.subject'))
                            ->htmlTemplate('emails/fruits_added_notification.html.twig')
                            ->from($this->adminEmail)
                            ->to($this->adminEmail)
                            ->context(['fruits' => $fruits])
                    );
                }
            }

            $this->entityManager->flush();
        } else {
            $io->error($response->getContent());
        }

        $io->success($this->translator->trans('commands.fruits.add_success'));

        return Command::SUCCESS;
    }

    /**
     * Prepare and save new Fruit object if such is not already exist in db
     * 
     * @param array $data given by Fruit API
     * 
     * @return Fruit|bool
     */
    private function saveFruit(array $data): Fruit|bool
    {
        if (!($this->entityManager->getRepository(Fruit::class)->findOneByName($data['name']))) {
            $fruit = new Fruit();
            $fruit->setName($data['name']);
            $fruit->setFamily(CheckIfExist::createIfNot($this->entityManager, Family::class, $data['family']));
            $fruit->setFrOrder(CheckIfExist::createIfNot($this->entityManager, Order::class, $data['order']));
            $fruit->setGenus(CheckIfExist::createIfNot($this->entityManager, Genus::class, $data['genus']));
            $fruit->setNutriotions($data['nutritions']);

            $this->entityManager->persist($fruit);

            return $fruit;
        }

        return false;
    }
}
