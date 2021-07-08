<?php declare(strict_types=1);

namespace ExEss\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class SetupGitHooksCommand extends Command
{
    /**
     * @var string
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
    protected static $defaultName = 'exess:setup:git-hooks';

    protected function configure(): void
    {

        $this
            ->setDescription('Sets up git-hooks for your project')
            ->addOption(
                'root',
                'root',
                InputOption::VALUE_OPTIONAL,
                'Path to root folder',
                '.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Preparing git-hooks');

        $this->setup($input->getOption('root'));

        $io->success('Git hooks copied!');
        return 0;
    }

    private function setup(string $root): void
    {
        /** @var SplFileInfo $file */
        foreach ($this->getFinder()->files()->in(__DIR__ . '/Template/hooks') as $file) {
            $this->getFileSystem()->remove($root . '/.git/hooks/' . $file->getFilename());
            $this->getFileSystem()->copy(
                $file->getRealPath(),
                $root . '/.git/hooks/' . $file->getFilename(),
                true
            );
            $this->getFileSystem()->chmod([$root . '/.git/hooks/' . $file->getFilename()], 0755);
        }
    }

    private function getFileSystem(): Filesystem
    {
        return new Filesystem();
    }

    private function getFinder(): Finder
    {
        return new Finder();
    }
}
