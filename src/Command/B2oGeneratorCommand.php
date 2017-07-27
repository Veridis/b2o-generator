<?php

namespace Veridis\Command;

use MarkovPHP\WordChain;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class B2oGeneratorCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('punchline')
            ->setDescription('Balance des punchlines')
            ->addArgument('source', InputArgument::OPTIONAL, 'Fichier source', __DIR__.'/../resources/punchlines.txt')
            ->addOption('theme', 't', InputOption::VALUE_OPTIONAL, 'Thème', null)
            ->addOption('length', 'l', InputOption::VALUE_OPTIONAL, 'Longueure d\'une chaine', 2)
            ->addOption('number', 'nb', InputOption::VALUE_OPTIONAL, 'Nombre de chaines', 8)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('source');
        $theme = $input->getOption('theme');
        $number = $input->getOption('number');
        $length = $input->getOption('length');

        if (!is_readable($file)) {
            $output->writeln('Impossifle de lire le fichier ' . $file);

            return;
        }

        $text = file_get_contents($file);
        $chainer = new WordChain($text, $length);
        $punchline = self::sanitize($chainer->generate($number, $theme));

        $output->writeln($punchline);
    }

    private static function sanitize($punch)
    {
        return ucfirst(strtolower($punch)).'.';
    }
}
