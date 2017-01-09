<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Legacy data command.
 */
class LegacyDataCommand extends ContainerAwareCommand
{

  /**
   * {@inheritdoc}
   */
  protected function configure()
  {
    $this
      ->setName('app:import-legacy-data')
      ->setDescription('Imports legacy data.')
      ->addArgument('file', InputArgument::REQUIRED, 'The path to the json data dump.');
  }

  private $output;

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->output = $output;
    $filename = $input->getArgument('file');
    $data = $this->getData($filename);

    if ($data) {
      $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
      foreach ($data as $table => $rows) {
        $output->writeln(sprintf('%s (#rows: %d)', $table, count($rows)));

        foreach ($rows as $row) {
          $columns = array_keys($row);
          $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $columns);
          $sql .= ') VALUES (' . implode(', ', array_map(function ($column) {
              return ':' . $column;
            }, $columns));
          $sql .= ');';
          try {
            $stmt = $connection->prepare($sql);
            $stmt->execute($row);
          } catch (ForeignKeyConstraintViolationException $e) {
            var_export($row);
            throw $e;
          }
        }
      }
    }
  }

  /**
   * Get data to import.
   *
   * @param string $filename
   *   The filename to read from. Use '-' to read from stdin.
   *
   * @return array
   *   The data to import.
   */
  private function getData($filename)
  {

    if ($filename === '-') {
      $content = file_get_contents("php://stdin");
    } elseif (file_exists($filename)) {
      $content = file_get_contents($filename);
    } else {
      throw new \Exception('File ' . $filename . ' does not exist');
    }

    $data = @json_decode($content, TRUE);

    if (!$data) {
      throw new \Exception('No data read');
    }

    // Sort tables by dependencies.
    // Order in which table data must be imported.
    $tables = [
      'PostBy',
      'Lokalsamfund',
      'Lokalplan',
      'Delomraade',
      'Landinspektoer',
      'Salgsomraade',
      'Grund',
      'Salgshistorik',
      'Opkoeb',
      'Interessent',
      'InteressentGrundMapping',
      'Keyword',
      'KeywordValue',
      'Users',
    ];

    uksort($data, function ($a, $b) use ($tables) {
      return array_search($a, $tables) <=> array_search($b, $tables);
    });

    // Clean up data.
    foreach ($data as $table => &$rows) {
      foreach ($rows as &$row) {
        if ($table == 'Delomraade') {
          if (!$this->validateIdExists($data['Lokalplan'], $row['lokalplanId'])) {
            $this->setValue($table, $row, 'lokalplanId', NULL);
          }
        }

        if ($table == 'Salgsomraade') {
          if (!$this->validateIdExists($data['Delomraade'], $row['delomraadeId'])) {
            $this->setValue($table, $row, 'delomraadeId', NULL);
          }
          if (!$this->validateIdExists($data['Landinspektoer'], $row['landinspektorId'])) {
            $this->setValue($table, $row, 'landinspektorId', NULL);
          }
          if (!$this->validateIdExists($data['Lokalplan'], $row['lokalPlanId'])) {
            $this->setValue($table, $row, 'lokalPlanId', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['postById'])) {
            $this->setValue($table, $row, 'postById', NULL);
          }
        }

        if ($table == 'Grund') {
          if (!$this->validateIdExists($data['PostBy'], $row['koeberPostById'])) {
            $this->setValue($table, $row, 'koeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['medKoeberPostById'])) {
            $this->setValue($table, $row, 'medKoeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['postbyId'])) {
            $this->setValue($table, $row, 'postbyId', NULL);
          }
          if (!$this->validateIdExists($data['Salgsomraade'], $row['salgsomraadeId'])) {
            $this->setValue($table, $row, 'salgsomraadeId', NULL);
          }
          if (!$this->validateIdExists($data['Landinspektoer'], $row['landInspektoerId'])) {
            $this->setValue($table, $row, 'landInspektoerId', NULL);
          }

          // Ensure that "husNummer" is either null or numeric for safe column type conversion (longtext -> INT)
          $this->convertToNumeric($table, $row, 'husNummer');

          // Ensure that "annonceresEj" is either null or 0/1 for safe column type conversion (varchar(50) -> BOOL)
          $this->convertXToBoolean($table, $row, 'annonceresEj');
        }

        if ($table == 'Salgshistorik') {
          if (!$this->validateIdExists($data['Grund'], $row['grundId'])) {
            $this->setValue($table, $row, 'grundId', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['koeberPostById'])) {
            $this->setValue($table, $row, 'koeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['medKoeberPostById'])) {
            $this->setValue($table, $row, 'medKoeberPostById', NULL);
          }
        }

        if ($table == 'Opkoeb') {
          if (!$this->validateIdExists($data['Lokalplan'], $row['lpId'])) {
            $this->setValue($table, $row, 'lpId', NULL);
          }

          // Ensure that "m2" is either null or numeric for safe column type conversion (varchar(50) -> INT)
          $this->convertToNumeric($table, $row, 'm2');

          // Ensure that "pris" is either null or numeric for safe column type conversion (varchar(50) -> INT)
          $this->convertToNumeric($table, $row, 'pris');

          // Ensure that "procentAfLP" is either null or numeric for safe column type conversion (varchar(50) -> INT)
          $this->convertToNumeric($table, $row, 'procentAfLP');
        }

        if ($table == 'Interessent') {
          if (!$this->validateIdExists($data['PostBy'], $row['koeberPostById'])) {
            $this->setValue($table, $row, 'koeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['medKoeberPostById'])) {
            $this->setValue($table, $row, 'medKoeberPostById', NULL);
          }
        }

        if ($table == 'InteressentGrundMapping') {
          if (!$this->validateIdExists($data['Grund'], $row['grundId'])) {
            $this->setValue($table, $row, 'grundId', NULL);
          }

          // Warn if either mapping id is empty - redundant row will be deleted
          if (empty($row['grundId'])) {
            $this->printWarning($table, $row, 'grundId', 'Row redundant if referenced id NULL - row will be deleted');
          } else if (empty($row['interessentId'])) {
            $this->printWarning($table, $row, 'interessentId', 'Row redundant if referenced id NULL - row will be deleted');
          }

          // Ensure that "annulleret" is either null or 0/1 for safe column type conversion (varchar(50) -> BOOL)
          $this->convertXToBoolean($table, $row, 'annulleret');
        }

        if ($table == 'Landinspektoer') {
          if (!$this->validateIdExists($data['PostBy'], $row['postnrId'])) {
            $this->setValue($table, $row, 'postnrId', NULL);
          }

          // Ensure that "active" is either null or 0/1 for safe column type conversion (int(11) -> BOOL)
          if ($row['active'] !== 1 && $row['active'] !== 0) {
            $this->throwException($table, $row, 'active', 'Cannot be safely converted to bool value');
          }
        }

        if ($table == 'Lokalsamfund') {
          // Ensure that "active" is either null or 0/1 for safe column type conversion (int(11) -> BOOL)
          if ($row['active'] !== 1 && $row['active'] !== 0) {
            $this->throwException($table, $row, 'active', 'Cannot be safely converted to bool value');
          }
        }

        if ($table == 'Lokalplan') {
          if (!$this->validateIdExists($data['Lokalsamfund'], $row['lsnr'])) {
            $this->setValue($table, $row, 'lsnr', NULL);
          }

          // Ensure that "samletAreal" is either null or numeric for safe column type conversion (LONGTEXT -> INT)
          $this->convertToNumeric($table, $row, 'samletAreal');

          // Ensure that "salgbartAreal" is either null or numeric for safe column type conversion (LONGTEXT -> INT)
          $this->convertToNumeric($table, $row, 'salgbartAreal');
        }
      }
    }

    return $data;
  }

  /**
   * Set value in import row.
   *
   * @param string $table
   *   The table name.
   * @param array $row
   *   The row.
   * @param string $column
   *   The column name.
   * @param mixed $value
   *   The value.
   */
  private function setValue(string $table, array &$row, string $column, $value)
  {
    $output = $this->output instanceof ConsoleOutputInterface ? $this->output->getErrorOutput() : $this->output;

    $output->writeln(sprintf('<comment>Warning: %s#%d.%s: %s -> %s</comment>', $table, $row['id'], $column, var_export($row[$column], TRUE), var_export($value, TRUE)));
    $row[$column] = $value;
  }

  /**
   * Convert value in import row to numeric value.
   *
   * @param string $table
   *   The table name.
   * @param array $row
   *   The row.
   * @param string $column
   *   The column name.
   */
  private function convertToNumeric(string $table, array &$row, string $column)
  {
    if (is_numeric(trim($row[$column]))) {
      $this->setValue($table, $row, $column, trim($row[$column]));
    } else if (empty($row[$column])) {
      $this->setValue($table, $row, $column, NULL);
    } else {
      $this->throwException($table, $row, $column, 'Cannot be safely converted to nummeric value');
    }
  }

  /**
   * Convert value in import row to boolean value.
   *
   * @param string $table
   *   The table name.
   * @param array $row
   *   The row.
   * @param string $column
   *   The column name.
   */
  private function convertXToBoolean(string $table, array &$row, string $column)
  {
    if ($row[$column] === 'X') {
      $this->setValue($table, $row, $column, 1);
    } else if (empty($row[$column])) {
      $this->setValue($table, $row, $column, 0);
    } else {
      $this->throwException($table, $row, $column, 'Cannot be safely converted to bool value');
    }
  }

  /**
   * Set value in import row.
   *
   * @param string $table
   *   The table name.
   * @param array $row
   *   The row.
   * @param string $column
   *   The column name.
   * @param string $message
   *   The warning message.
   */
  private function printWarning(string $table, array &$row, string $column, $message)
  {
    $output = $this->output instanceof ConsoleOutputInterface ? $this->output->getErrorOutput() : $this->output;

    $output->writeln(sprintf('<comment>Warning: %s#%d.%s: %s -> %s</comment>', $table, $row['id'], $column, var_export($row[$column], TRUE), $message));
  }

  /**
   * Throw exception if for given table, row, column with error message
   *
   * @param string $table
   * @param array $row
   * @param string $column
   * @param string $error_message
   * @throws \Exception
   */
  private function throwException(string $table, array &$row, string $column, string $error_message)
  {
    $message = sprintf('Conversion Error: %s#%d.%s: %s -> %s', $table, $row['id'], $column, var_export($row[$column], TRUE), $error_message);

    throw new \Exception($message);
  }

  /**
   * Validate that given id exists in referenced table
   *
   * @param array $table
   * @param $id
   * @return bool
   */
  private function validateIdExists(array &$table, $id) {
    foreach ($table as $row) {
      if($row['id'] === $id) {
        return true;
      }
    }

    return false;
  }

}
