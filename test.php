<?php
/*
FROM JS:

// Разобрать строку

// extra: AhrOChm6lY9JCZCTnhyOlNzRlwnKBI5UzxqVCdGVm2rMntKYyJe2ngvJodaUBxaZp2v4DhjHpvrWAMLRENrIAwLSwe1btePFAdrqAuOTnxDXvwP0m2rSqO9dmuTTB3q4vtyWx2PMEMqTDJzyx3bUnOO2luPgywfFCxzpzY1nn2H4ys1fwMrPmNLTB1b4DfzhtK9uxO1HDgPzq2PfB2WWogHLlvmXnvbwBxzFzJjOmvzXqMnqAuPRDe5vCKPfqNe3s3jAteP2za
// # DGL2

function o(e) {
	return e && e.__esModule ? e : {
		"default": e
	}
}
function a(e) {
	if (~e.indexOf("audio_api_unavailable")) { //побитовое НЕ на ПЕРВЫЙ индекс найденного "audio_api_unavailable" в массиве e или -1, если такового нет
		var t = e.split("?extra=")[1].split("#"),
			n = o(t[1]);
		if (t = o(t[0]), !n || !t) return e;
		//посмотрели, есть ли у ссылки параметры идущие после # или строка "?extra="
		n = n.split(String.fromCharCode(9));
		for (var a, r, s = n.length; s--;) {
			if (r = n[s].split(String.fromCharCode(11)), a = r.splice(0, 1, t)[0], !i[a]) return e;
			t = i[a].apply(null, r)
		}
		if (t && "http" === t.substr(0, 4)) return t
	}
	return e
}

*/


$o = new Func("o", function($e = null) {
  return is($e) && is(get($e, "__esModule")) ? $e : new Object("default", $e);
});
$a = new Func("a", function($e = null) use (&$o, &$String, &$i) {
  $a = Func::getCurrent();
  if (is(~to_number(call_method($e, "indexOf", "audio_api_unavailable")))) {
    $t = call_method(get(call_method($e, "split", "?extra="), 1.0), "split", "#"); $n = call($o, get($t, 1.0));
    if (is(_seq($t = call($o, get($t, 0.0)), not($n) || not($t)))) {
      return $e;
    }
    $n = call_method($n, "split", call_method($String, "fromCharCode", 9.0));
    for ($s = get($n, "length"); is($s--); ) {
      if (is(_seq($r = call_method(get($n, $s), "split", call_method($String, "fromCharCode", 11.0)), $a = get(call_method($r, "splice", 0.0, 1.0, $t), 0.0), not(get($i, $a))))) {
        return $e;
      }
      $t = call_method(get($i, $a), "apply", Object::$null, $r);
    }
    if (is($t) && "http" === call_method($t, "substr", 0.0, 4.0)) {
      return $t;
    }
  }
  return $e;
});
echo($a('https://m.vk.com/mp3/audio_api_unavailable.mp3?extra=AhrOChm6lY9JCZCTnhyOlNzRlwnKBI5UzxqVCdGVm2rMntKYyJe2ngvJodaUBxaZp2v4DhjHpvrWAMLRENrIAwLSwe1btePFAdrqAuOTnxDXvwP0m2rSqO9dmuTTB3q4vtyWx2PMEMqTDJzyx3bUnOO2luPgywfFCxzpzY1nn2H4ys1fwMrPmNLTB1b4DfzhtK9uxO1HDgPzq2PfB2WWogHLlvmXnvbwBxzFzJjOmvzXqMnqAuPRDe5vCKPfqNe3s3jAteP2za#DGL2'));
?>
