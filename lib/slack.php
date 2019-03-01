<?php
function send_to_slack($text, $webhook_url) {
  $message = array(
    'username' => 'ブックマークアプリ',
    'text' => '「ブックマークアプリ」で以下のメッセージがありました。' . "\n\n" . $text
  );
  $options = array(
    'http' => array(
      'method' => 'POST',
      'header' => 'Content-Type: application/json',
      'content' => json_encode($message),
    )
  );
  $response = file_get_contents($webhook_url, false, stream_context_create($options));
  return $response === 'ok';
}
