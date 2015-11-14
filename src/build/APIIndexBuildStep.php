<?hh

namespace HHVM\UserDocumentation;

final class APIIndexBuildStep extends BuildStep {
  public function buildAll(): void {
    Log::i("\nAPIIndexBuild");

    $sources = self::findSources(BuildPaths::MERGED_YAML, Set{'yml'});
    sort($sources);

    $this->createIndex($sources);
  }

  private function createIndex(
    Iterable<string> $list,
  ): void {
    $index = $this->generateIndexData($list);
    /* TODO: Use hack-codegen once
     * https://github.com/facebook/hack-codegen/issues/7 is addressed? */
    $code = file_get_contents(__DIR__.'/../APIIndexData.hhi');

    $re = $s ==> '/'.preg_quote($s, '/').'/';

    $replacements = [
      $re('<?hh // decl') => '<?php',
      '/\): [^{]+{/' => ') {',
      $re('/* CODEGEN GOES HERE */') => 'return '.var_export($index, true).';',
    ];
    foreach ($replacements as $pattern => $replacement) {
      $code = preg_replace($pattern, $replacement, $code);
    }
    file_put_contents(
      BuildPaths::APIDOCS_INDEX,
      $code,
    );
  }

  private function generateIndexData(
    Iterable<string> $list,
  ): APIIndexShape {

    Log::i("\nCreate Index");
    $out = shape(
      'class' => [],
      'interface' => [],
      'trait' => [],
      'function' => [],
    );
    foreach ($list as $yaml_path) {
      Log::v('.');
      $data = ((): BaseYAML ==> \Spyc::YAMLLoad($yaml_path))(); // cast :p

      $type = $data['type'];
      switch ($type) {
        case APIDefinitionType::FUNCTION_DEF:
          $docs = (
            (): FunctionDocumentation ==> /* UNSAFE_EXPR */ $data['data']
          )();

          $idx = strtr($docs['name'], "\\", '.');
          $md_path = FunctionMarkdownBuilder::getOutputFileName($docs);
          $html_path = APIHTMLBuildStep::getOutputFileName($md_path);

          $out['function'][$idx] = shape(
            'name' => $docs['name'],
            'htmlPath' => $html_path,
          );
          break;
        case APIDefinitionType::CLASS_DEF:
        case APIDefinitionType::INTERFACE_DEF:
        case APIDefinitionType::TRAIT_DEF:
          $class = (
            (): ClassDocumentation ==> /* UNSAFE_EXPR */ $data['data']
          )();


          $methods = [];
          foreach ($class['methods'] as $method) {
            $idx = strtr($method['name'], "\\", '.');
            $md_path = MethodMarkdownBuilder::getOutputFileName(
              $type,
              $class,
              $method,
            );
            $html_path = APIHTMLBuildStep::getOutputFileName($md_path);
            $methods[$idx] = shape(
              'name' => $method['name'],
              'className' => $class['name'],
              'htmlPath' => $html_path,
            );
          }

          $md_path = ClassMarkdownBuilder::getOutputFileName(
            $type,
            $class,
          );
          $html_path = APIHTMLBuildStep::getOutputFileName($md_path);
      
          $idx = strtr($class['name'], "\\", '.');
          $entry = shape(
            'name' => $class['name'],
            'htmlPath' => $html_path,
            'methods' => $methods,
          );

          switch ($type) {
            case APIDefinitionType::CLASS_DEF:
              $out['class'][$idx] = $entry;
              break;
            case APIDefinitionType::INTERFACE_DEF:
              $out['interface'][$idx] = $entry;
              break;
            case APIDefinitionType::TRAIT_DEF:
              $out['trait'][$idx] = $entry;
              break;
            case APIDefinitionType::FUNCTION_DEF:
              invariant_violation('unreachable');
          }
          break;
      }
    }

    return $out;
  }
}