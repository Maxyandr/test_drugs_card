<?php

namespace App\Command;

use App\Service\ProductService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:scrape',
    description: 'Test task for Drugs Code',
)]
class ScrapeCommand extends Command
{
    public function __construct(
        private ProductService $productService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('lang', InputArgument::OPTIONAL, 'Scrape by language', 'ua')
            ->addOption(
                'url',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Scrape url'
            )
            ->addOption(
                'except',
                'x',
                InputOption::VALUE_OPTIONAL |
                InputOption::VALUE_IS_ARRAY,
                'Except list of langs'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('Start'. PHP_EOL);

        $lang = $input->getArgument('lang');
        $lang = $lang === 'all' ? null : $lang;

        $except = $input->getOption('except') ?? [];
        $output->writeln('<info>Scraped data except languages: '.implode(',', $except).'</info>');

        $url = $input->getOption('url');

        if ($url) {
            $output->write('Scraping url:' . $url . PHP_EOL);
            $this->productService->getAndSaveProductByUrl($url);
            return Command::SUCCESS;
        }

        $output->write('Scraping all product data by lang ' . $lang . PHP_EOL);
        $this->productService->getAndSaveProductData($lang, $except);

        $output->write('End'. PHP_EOL);

        return Command::SUCCESS;
    }
}
