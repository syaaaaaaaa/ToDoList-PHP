<?php
// XSS対策のためのHTMLエスケープ
function es(array|string $data, string $charset='UTF-8'):mixed {
  // $dataが配列のとき
  if (is_array($data)){
    // 再帰呼び出し
    return array_map(__METHOD__, $data);
  } else {
    // HTMLエスケープを行う
    return htmlspecialchars(string:$data, flags:ENT_QUOTES, encoding:$charset);
  }
}

// 配列の文字エンコードのチェックを行う
function cken(array $data):bool{
  $result = true;
  foreach ($data as $key => $value) {
    if (is_array($value)){
      // 含まれている値が配列のとき文字列に連結する
      $value = implode("", $value);
    }
    if (!mb_check_encoding($value)){
      // 文字エンコードが一致しないとき
      $result = false;
      // foreachでの走査をブレイクする
      break;
    }
  }
  return $result;
}
// ?>
