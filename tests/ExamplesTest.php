<?hh // strict

namespace HHVM\UserDocumentation\Tests;

use HHVM\UserDocumentation\LocalConfig;

/**
 * @large
 */
class ExamplesTest extends \PHPUnit_Framework_TestCase {
  const string TEST_RUNNER = LocalConfig::HHVM_TREE.'/hphp/test/run';

  public function testExamplesOutput(): void {
    $this->runExamples(Vector {
    });
  }

  public function testExamplesTypecheck(): void {
    $hh_server = dirname(PHP_BINARY).'/hh_server';
    if (!file_exists($hh_server)) {
      $this->markTestSkipped("Couldn't find hh_server");
    }

    $this->runExamples(Vector {
      '--typechecker',
    });
  }

  private function getHHVMPath(): string {
    return PHP_BINARY;
  }

  <<__Memoize>>
  private function getHHServerPath(): string {
    $hh_server = dirname(PHP_BINARY).'/hh_server';
    if (!file_exists($hh_server)) {
      $this->markTestSkipped("Couldn't find hh_server");
    }
    return $hh_server;
  }

  private function runExamples(Vector<string> $extra_args): void {
    $command = Vector {
      PHP_BINARY,
      self::TEST_RUNNER,
      '-m', 'interp',
    };
    $command->addAll($extra_args);
    $command[] = LocalConfig::ROOT.'/guides';

    $command_str = implode(' ', $command->map($arg ==> escapeshellarg($arg)));
    $exit_code = null;
    $output = null;

    $env = Vector {
      'HHVM_BIN='.escapeshellarg($this->getHHVMPath()),
      'HH_SERVER_BIN='.escapeshellarg($this->getHHServerPath()),
    };

    $command_str =
      implode('', $env->map($x ==> $x.' ')).$command_str;

    exec($command_str, /*&*/ $output, /*&*/ $exit_code);

    $this->assertSame(0, $exit_code, implode("\n", $output));
  }
}
