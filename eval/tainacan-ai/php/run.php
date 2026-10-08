<?php
// Usage: wp eval-file run.php <blueprint.json> <cases.json> <images dir> [key=value ...]
//   model=<anthropic model id>          this run only ("" keeps the site's)
//   effort=<low|medium|high|xhigh|max>  Anthropic output_config.effort, this run only
//   max_tokens=<n>                      this run only (default: the Tainacan AI setting)
//   mode=single|multi                   single (default): the first view of each case through the real REST endpoint
//                                       (/tainacan-ai/v1/analyze, exactly what the item form does).
//                                       multi: every view of a case in one request ("Imagem k de N"), see multi_analyze().
//   views=first|all                     multi mode only: all views (default) or the first only (a control for the prototype).
//   cases=<id,id,...>                   only these cases (default: all in cases.json).
// Uploads each image as temporary media named after the case (neutral names, no hints), analyzes, scores
// Denominação/Classificação against cases.json, prints Markdown (summary first) and deletes the uploads.
use Tainacan\AI\Extraction\DocumentAnalyzer;
use Tainacan\AI\Extraction\EvidenceInstructions;
use Tainacan\AI\Extraction\ExtractionMetadata;
use Tainacan\AI\Support\CoreAI;

$blueprint = json_decode(file_get_contents(array_shift($args)), true);
$cases = json_decode(file_get_contents(array_shift($args)), true);
$img_dir = rtrim(array_shift($args), '/');
$opt = ['model' => '', 'effort' => '', 'max_tokens' => '', 'mode' => 'single', 'views' => 'all', 'cases' => ''];
foreach ($args as $a) {
  if (preg_match('/^(\w+)=(.*)$/s', $a, $m) && array_key_exists($m[1], $opt)) $opt[$m[1]] = $m[2];
}
$multi = $opt['mode'] === 'multi';
if ($opt['cases'] !== '') {
  $want = array_map('trim', explode(',', $opt['cases']));
  $cases = array_values(array_filter($cases, fn ($c) => in_array($c['id'], $want, true)));
}

// Optional model override for this run only (the site's own preference lives in mu-plugins/museu-ai.php).
if ($opt['model'] !== '') {
  add_filter('wpai_preferred_vision_models', fn ($models) => array_merge([['anthropic', $opt['model']]], $models), 99);
}
// Optional effort (Anthropic output_config.effort) and max_tokens for this run only: set on the request's model
// config after mu-plugins/museu-ai.php has filled in its own (priority 10), so the site's settings stay untouched.
$effort = $opt['effort'];
$max_tokens = (int) $opt['max_tokens'];
add_action('wp_ai_client_before_generate_result', function ($event) use ($effort, $max_tokens) {
  $config = $event->getModel()->getConfig();
  if ($effort !== '') $config->setCustomOption('output_config', ['effort' => $effort]);
  if ($max_tokens > 0) $config->setMaxTokens($max_tokens);
  // Size of the system instruction actually on the request (the mu-plugin restores it; 0 would mean it was dropped).
  $GLOBALS['eval_system_chars'] = mb_strlen((string) $config->getSystemInstruction());
}, 20);
// Usage details Tainacan AI doesn't report: input, output and thinking tokens, and why generation stopped.
add_action('wp_ai_client_after_generate_result', function ($event) {
  $r = $event->getResult(); $u = $r->getTokenUsage();
  $GLOBALS['eval_usage'] = ['in' => $u->getPromptTokens(), 'out' => $u->getCompletionTokens(), 'thinking' => $u->getThoughtTokens(),
    'stop' => $r->getCandidates()[0]->getFinishReason()->value];
});
// Which file was actually sent (mu-plugins/museu-ai.php may swap in a downscaled copy while analyzing).
add_filter('get_attached_file', function ($file) {
  if (!empty($GLOBALS['museu_ai_analyzing']) && $file && ($s = @getimagesize($file))) $GLOBALS['eval_sent'][] = "{$s[0]}x{$s[1]}";
  return $file;
}, 99);

$max_edge = (int) apply_filters('museu_ai_image_max_edge', (int) get_option('museu_ai_image_max_edge', 0));
$name = $blueprint['collection']['name'];
$col = \Tainacan\Repositories\Collections::get_instance()->fetch(['title' => $name, 'posts_per_page' => 1], 'OBJECT');
if (!$col) { echo "Collection '$name' not found; apply the blueprint first.\n"; exit(1); }
$col_id = $col[0]->get_id();
wp_set_current_user(get_users(['role' => 'administrator', 'number' => 1])[0]->ID);

// Upload every image this run needs; delete them however the run ends.
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$uploads = [];
register_shutdown_function(function () use (&$uploads) {
  foreach ($uploads as $id) wp_delete_attachment($id, true);
});
$upload = function (string $file) use (&$uploads, $img_dir) {
  $tmp = wp_tempnam($file);
  copy("$img_dir/$file", $tmp);
  $id = media_handle_sideload(['name' => $file, 'tmp_name' => $tmp], 0, pathinfo($file, PATHINFO_FILENAME));
  if (is_wp_error($id)) { @unlink($tmp); throw new RuntimeException("upload $file: " . $id->get_error_message()); }
  return $uploads[] = $id;
};

/**
 * Multi-photo prototype. Tainacan AI 0.2.0 sends one image per analysis; this composes the same request the
 * plugin builds for an image (DocumentAnalyzer::analyze_image + CoreAI::generate_json_from_prompt) but with all
 * views, each preceded by a "Imagem k de N" label, then normalizes the answer the way DocumentAnalyzer::run_analysis
 * does. The system prompt is the plugin's own (AnalysisPromptComposer::get_context, unchanged); only the user
 * message differs. Private plugin methods are called by reflection so nothing is copied.
 */
function multi_analyze(int $col_id, array $ids): array|WP_Error {
  $call = function (string $class, string $method, ?object $obj, ...$a) {
    $m = new ReflectionMethod($class, $method); $m->setAccessible(true); return $m->invoke($obj, ...$a);
  };
  $an = (new DocumentAnalyzer())->set_context($col_id);
  // Fires tainacan_ai_analysis_prompt, so mu-plugins/museu-ai.php restores the system instruction as in production.
  $ctx = $call(DocumentAnalyzer::class, 'resolve_analysis_prompt_context', $an, EvidenceInstructions::MODE_IMAGE);
  if (is_wp_error($ctx)) return $ctx;
  $n = count($ids);
  $intro = 'Analyze the attached images and extract the requested metadata. '
    . ($n > 1 ? "The $n images are different views of the same object (Imagem 1 is the main view): catalogue the object, not each photo, using what any view shows, and in evidence say which image supports each value (e.g. \"Imagem 2\")." : '');
  $builder = wp_ai_client_prompt(trim($intro));
  $GLOBALS['museu_ai_analyzing'] = true; // same file the plugin would send (downscaled copy if max edge > 0)
  foreach ($ids as $k => $id) {
    $file = get_attached_file($id);
    $mime = get_post_mime_type($id);
    $builder->with_text(sprintf('Imagem %d de %d:', $k + 1, $n));
    $builder->with_file('data:' . $mime . ';base64,' . base64_encode(file_get_contents($file)), $mime);
  }
  $builder->with_text("---\n\n" . ExtractionMetadata::get_instance()->build_response_closing_reminder($ctx['expected_slugs']));
  // Same generation options as the plugin. Temperature is left out: on WP 7 the plugin's method_exists() check
  // drops it too, so production never sends it. System prompt and max_tokens come from the mu-plugin, as in production.
  $o = \Tainacan\AI\Plugin::get_options();
  $call(CoreAI::class, 'builderUsingRequestTimeout', null, $builder, (float) ($o['request_timeout'] ?? 120));
  $call(CoreAI::class, 'apply_vision_model_preferences', null, $builder);
  $t0 = microtime(true);
  $result = $call(CoreAI::class, 'builderGenerateTextResult', null, $builder);
  if (is_wp_error($result)) return $result;
  $raw = $call(CoreAI::class, 'extract_result_text', null, $result);
  $meta = $call(CoreAI::class, 'finalize_request_meta', null, $call(CoreAI::class, 'extract_request_meta_from_result', null, $result), $raw, $t0);
  $json = $call(CoreAI::class, 'parse_json_response', null, $raw);
  if ($json === null) return new WP_Error('json_parse_error', 'AI returned invalid JSON: ' . mb_substr($raw, 0, 200));
  $norm = ExtractionMetadata::get_instance()->complete_expected_fields_with_slugs(EvidenceInstructions::normalize_metadata($json), $ctx['expected_slugs']);
  $norm = $call(DocumentAnalyzer::class, 'normalize_metadata_values_for_api', $an, $norm, $ctx['fields']);
  // Tesauro Museus resolves Denominação/Classificação on the REST response; feed it the same shape.
  $req = new WP_REST_Request('POST', '/tainacan-ai/v1/analyze');
  $req->set_param('collection_id', $col_id);
  $res = \TesauroMuseus\Tainacan_AI::resolve_analysis(new WP_REST_Response(['result' => ['ai_metadata' => $norm]], 200), [], $req);
  $data = $res->get_data()['result'];
  $data['model_used'] = $meta['model_used'] ?? '';
  $data['tokens_used'] = $meta['tokens_used'] ?? 0;
  return $data;
}

function first_label($f) {
  if (!is_array($f)) return null;
  $v = $f['label'] ?? $f['value'] ?? null;
  if (is_array($v)) $v = $v[0] ?? null;
  return is_string($v) && $v !== '' ? $v : null;
}
function field(array $meta, string $suffix) {
  foreach ($meta as $k => $f) if (str_ends_with($k, $suffix)) return $f;
  return null;
}
function md($s) { return str_replace('|', '\|', (string) $s); }

$rows = []; $out = '';
$tok = ['in' => 0, 'out' => 0, 'n' => 0];
foreach ($cases as $case) {
  $files = $multi && $opt['views'] === 'all' ? $case['images'] : [$case['images'][0]];
  $GLOBALS['eval_usage'] = null; $GLOBALS['eval_sent'] = []; $GLOBALS['eval_system_chars'] = null;
  $t0 = microtime(true);
  try {
    $ids = array_map($upload, $files);
    if ($multi) {
      $r = multi_analyze($col_id, $ids);
    } else {
      $req = new WP_REST_Request('POST', '/tainacan-ai/v1/analyze');
      foreach (['attachment_id' => $ids[0], 'collection_id' => $col_id, 'force_refresh' => true] as $k => $v) $req->set_param($k, $v);
      $res = rest_do_request($req); $d = $res->get_data();
      $r = $res->get_status() === 200 ? $d['result'] : new WP_Error($d['code'] ?? 'error', $d['message'] ?? 'HTTP ' . $res->get_status());
    }
  } catch (Throwable $e) {
    $r = new WP_Error('exception', $e->getMessage());
  }
  $secs = round(microtime(true) - $t0, 1);
  $u = $GLOBALS['eval_usage'];
  if ($u) { $tok['in'] += $u['in']; $tok['out'] += $u['out']; $tok['n']++; }
  $usage = $u ? sprintf('in %d, out %d, thinking %s, %s', $u['in'], $u['out'], $u['thinking'] ?? '?', $u['stop']) : 'no usage';
  $sent = implode(' + ', $GLOBALS['eval_sent']) ?: '?';
  $head = sprintf('## %s: %d view%s (%s; %s; system prompt %s chars; images %s; %s s)', $case['id'], count($files), count($files) > 1 ? 's' : '',
    is_wp_error($r) ? '?' : ($r['model_used'] ?? '?'), $usage, $GLOBALS['eval_system_chars'] ?? '?', $sent, $secs);
  $exp = $case['expected'];
  if (is_wp_error($r)) {
    $out .= "$head\nERROR {$r->get_error_code()}: {$r->get_error_message()}\n\n";
    $rows[] = [$case['id'], count($files), 'ERROR', '', '✗', '', '✗', '', $u];
    continue;
  }
  $meta = $r['ai_metadata'];
  $den = first_label(field($meta, 'denominacao'));
  $cls = first_label(field($meta, 'classificacao'));
  $dat = field($meta, 'dataprod')['value'] ?? null;
  $aut = field($meta, 'autor')['value'] ?? null;
  // Denominação: exact, an accepted alternative, or (expected null) nothing.
  $alts = $exp['denominacao_alternativas'] ?? [];
  $den_ok = $den === $exp['denominacao'] ? ($den === null ? 'null' : 'exact') : (in_array($den, $alts, true) ? 'alt' : null);
  $cls_ok = $cls === $exp['classificacao'];
  $dat_s = is_array($dat) ? implode('; ', $dat) : (string) $dat;
  $exp_dat = $exp['data_producao'];
  $dat_ok = $exp_dat === null ? $dat_s === '' : $dat_s !== '' && str_contains($dat_s, (string) $exp_dat);
  $rows[] = [$case['id'], count($files), $den ?? '—', $exp['denominacao'] ?? '—', $den_ok ? '✓ ' . $den_ok : '✗', $cls ?? '—', $cls_ok ? '✓' : '✗',
    ($dat_s ?: '—') . ' / ' . ($exp_dat ?? '—') . ($dat_ok ? ' ✓' : ' ✗'), $u, $aut];

  $out .= "$head\n";
  $out .= sprintf("**Denominação** %s (expected %s%s) — **Classificação** %s %s\n\n", $den_ok ? '✓' : '✗', json_encode($exp['denominacao'], JSON_UNESCAPED_UNICODE),
    $alts ? ', or ' . implode(', ', $alts) : '', $cls_ok ? '✓' : '✗', $cls_ok ? '' : '(expected ' . json_encode($exp['classificacao'], JSON_UNESCAPED_UNICODE) . ')');
  foreach ($meta as $k => $f) {
    $shown = $f['label'] ?? $f['value'];
    $out .= "- $k: " . json_encode($shown, JSON_UNESCAPED_UNICODE) . "\n";
    if ($f['value'] !== null) $out .= '  evidence: ' . json_encode($f['evidence'] ?? null, JSON_UNESCAPED_UNICODE) . "\n";
    if (!empty($f['pending_new_terms'])) $out .= '  new terms: ' . json_encode(array_column($f['pending_new_terms'], 'label'), JSON_UNESCAPED_UNICODE) . "\n";
  }
  // What the Tesauro Museus matcher saw and picked (names tried, top candidates).
  if (isset($r['tesauro'])) {
    $t = $r['tesauro'];
    $out .= '  tesauro: names=' . json_encode($t['names'], JSON_UNESCAPED_UNICODE) . ' -> ' . json_encode(array_map(fn ($c) => "{$c['label']} ({$c['method']} {$c['score']})", $t['candidates']), JSON_UNESCAPED_UNICODE) . "\n";
  }
  $out .= "\n";
}

// Summary first, then the details.
$pass = count(array_filter($rows, fn ($r) => str_starts_with($r[4], '✓') && $r[6] === '✓'));
$den_n = count(array_filter($rows, fn ($r) => str_starts_with($r[4], '✓')));
$cls_n = count(array_filter($rows, fn ($r) => $r[6] === '✓'));
$dat_n = count(array_filter($rows, fn ($r) => str_ends_with($r[7], '✓')));
$n = count($rows);
echo "mode: " . ($multi ? 'multi (' . ($opt['views'] === 'all' ? 'all views' : 'first view only') . ', prototype)' : 'single (first view, REST endpoint)')
  . ', model: ' . ($opt['model'] ?: 'site default') . ', effort: ' . ($effort ?: 'default') . ', max_tokens: ' . ($max_tokens ?: 'site')
  . ", museu_ai_image_max_edge: $max_edge" . ($max_edge ? '' : ' (originals sent)') . "\n";
echo "collection: $name (#$col_id)\n\n";
echo "**Pass: $pass/$n** (Denominação and Classificação both right). Denominação $den_n/$n, Classificação $cls_n/$n, Data de Produção $dat_n/$n (informational).\n";
if ($tok['n']) printf("Tokens: %d in + %d out over %d requests (mean %d in, %d out).\n", $tok['in'], $tok['out'], $tok['n'], $tok['in'] / $tok['n'], $tok['out'] / $tok['n']);
echo "\n| Case | Views | Denominação | Expected | ✓ | Classificação | ✓ | Data de Produção (got / expected) | Autor | Tokens in/out |\n|---|---|---|---|---|---|---|---|---|---|\n";
foreach ($rows as $r) {
  $aut = $r[9] ?? null;
  printf("| %s | %d | %s | %s | %s | %s | %s | %s | %s | %s |\n", $r[0], $r[1], md($r[2]), md($r[3]), $r[4], md($r[5]), $r[6], md($r[7]),
    md(is_array($aut) ? implode('; ', $aut) : ($aut ?? '—')), $r[8] ? "{$r[8]['in']}/{$r[8]['out']}" : '—');
}
echo "\n$out";
