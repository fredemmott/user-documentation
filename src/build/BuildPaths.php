<?hh // strict

namespace HHVM\UserDocumentation;

abstract final class BuildPaths {
  const string SYSTEMLIB_YAML = LocalConfig::BUILD_DIR.'/systemlib-yaml';
  const string HHI_YAML = LocalConfig::BUILD_DIR.'/hhi-yaml';
  const string MERGED_YAML = LocalConfig::BUILD_DIR.'/merged-yaml';

  const string APIDOCS_MARKDOWN = LocalConfig::BUILD_DIR.'/api-markdown';
  const string APIDOCS_HTML = LocalConfig::BUILD_DIR.'/api-html';
  const string APIDOCS_INDEX = LocalConfig::BUILD_DIR.'/api-index.php';
  const string APIDOCS_LEGACY_REDIRECTS
    = LocalConfig::BUILD_DIR.'/api-legacy-redirects.php';

  const string PHP_DOT_NET_INDEX_JSON =
    LocalConfig::BUILD_DIR.'/php-dot-net-index.json';

  const string GUIDES_MARKDOWN = LocalConfig::ROOT.'/guides';
  const string GUIDES_GENERATED_MARKDOWN
    = LocalConfig::BUILD_DIR.'/guides-generated-markdown';
  const string GUIDES_HTML = LocalConfig::BUILD_DIR.'/guides-html';
  const string GUIDES_INDEX = LocalConfig::BUILD_DIR.'/guides-index.php';
  const string GUIDES_SUMMARY = LocalConfig::BUILD_DIR.'/guides-summary.php';

  // FooBar => URL (for markdown)
  const string UNIFIED_INDEX_JSON
    = LocalConfig::BUILD_DIR.'/unified-index.json';
  // foobar => URL (for /j/ URLs)
  const string JUMP_INDEX
    = LocalConfig::BUILD_DIR.'/jump-index.php';

  const string PHP_DOT_NET_API_INDEX
    = LocalConfig::BUILD_DIR.'/php-dot-net-api-index.php';
  const string PHP_DOT_NET_ARTICLE_REDIRECTS
    = LocalConfig::BUILD_DIR.'/php-dot-net-article-redirects.php';
  const string PHP_INI_SUPPORT_IN_HHVM
    = LocalConfig::BUILD_DIR.'/php-ini-support-in-hhvm.php';

  const string CORE_CSS = LocalConfig::BUILD_DIR.'/main.css';
  const string SYNTAX_HIGHLIGHT_CSS =
    LocalConfig::BUILD_DIR.'/syntax-highlighting.css';
  const string SITE_MAP = LocalConfig::BUILD_DIR.'/sitemap.txt';

  const string STATIC_RESOURCES_MAP =
   LocalConfig::BUILD_DIR.'/static_resources.php';
  const string STATIC_RESOURCES_MAP_JSON =
   LocalConfig::BUILD_DIR.'/static_resources.json';

  const string FASTROUTE_CACHE =
    LocalConfig::BUILD_DIR.'/route.cache';

  const string BUILD_ID = LocalConfig::BUILD_DIR.'/build_id.txt';
}
