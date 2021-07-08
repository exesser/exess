<?php declare(strict_types=1);

namespace ExEss\Bundle\CmsBundle\Command\Crud;

use Doctrine\DBAL\Connection;
use ExEss\Bundle\CmsBundle\Command\AbstractAdminCommand;
use ExEss\Bundle\CmsBundle\Helper\DataCleaner;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends AbstractAdminCommand
{
    private const INSERT_SQL_TEMPLATE = 'INSERT INTO %s (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s; ';

    /**
     * @var string
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
    protected static $defaultName = 'exess:crud:install';

    private string $crudRecordsPath;

    private Connection $connection;

    public function __construct(string $crudRecordsPath, Connection $connection)
    {
        parent::__construct();

        $this->crudRecordsPath = $crudRecordsPath;
        $this->connection = $connection;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title("ExEss CRUD install");

        $this->connection->transactional(function (Connection $connection): void {
            foreach ($this->generateSql() as $table => $queries) {
                $this->io->text("Importing " . \count($queries) . " records into $table");
                foreach ($queries as $query) {
                    $connection->executeQuery($query);
                }
            }
        });

        return 0;
    }

    private function generateSql(): array
    {
        $sql = [];

        foreach ($this->yieldCrudRecords() as $table => $record) {
            $record = \array_map(
                function ($fieldValue) {
                    if ($fieldValue === false) {
                        return 0;
                    }

                    if ($fieldValue === true) {
                        return 1;
                    }

                    if (\is_string($fieldValue)) {
                        return "'" . $fieldValue . "'";
                    }

                    if ($fieldValue === null) {
                        return 'null';
                    }

                    return $fieldValue;
                },
                $record
            );

            $update = \array_map(
                function ($value, $key): ?string {
                    return $key !== 'id' ? \sprintf('%s=%s', $key, $value) : null;
                },
                $record,
                \array_keys($record)
            );

            $update = \implode(',', \array_filter($update));

            $sql[$table] = $sql[$table] ?? [];
            $sql[$table][] = \sprintf(
                self::INSERT_SQL_TEMPLATE,
                $table,
                \implode(',', \array_keys($record)),
                \implode(',', \array_values($record)),
                $update
            );
        }

        return $sql;
    }

    protected function yieldCrudRecords(): \Generator
    {
        if (!\is_dir($path = $this->crudRecordsPath)) {
            throw new \DomainException("Path $path doesn't exist!");
        }
        foreach (\glob("$path/*.json") as $file) {
            $tables = DataCleaner::jsonDecode(\file_get_contents($file));
            foreach ($tables as $table => $records) {
                foreach ($records as $record) {
                    yield $table => $record;
                }
            }
        }
    }
}
