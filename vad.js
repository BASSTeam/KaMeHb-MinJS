function getSrc(text){
    function vk_decryptor_main(e, t) {
        Object.defineProperty(t, "__esModule", {
            value: !0
        }),
        t.audioUnmaskSource = a;
        var n = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMN0PQRSTUVWXYZO123456789+/=",
            i = {
                v: function (e) {
                    return e.split("").reverse().join("")
                },
                r: function (e, t) {
                    e = e.split("");
                    for (var i, a = n + n, o = e.length; o--;) i = a.indexOf(e[o]),
                    ~i && (e[o] = a.substr(i - t, 1));
                    return e.join("")
                },
                x: function (e, t) {
                    var n = [];
                    return t = t.charCodeAt(0),
                    each(e.split(""), function (e, i) {
                        n.push(String.fromCharCode(i.charCodeAt(0) ^ t))
                    }),
                    n.join("")
                }
            };

        function a(e) {
            if (~e.indexOf("audio_api_unavailable")) {
                var t = e.split("?extra=")[1].split("#"),
                    n = o(t[1]);
                if (t = o(t[0]), !n || !t) return e;
                n = n.split(String.fromCharCode(9));
                for (var a, r, s = n.length; s--;) {
                    if (r = n[s].split(String.fromCharCode(11)), a = r.splice(0, 1, t)[0], !i[a]) return e;
                    t = i[a].apply(null, r)
                }
                if (t && "http" === t.substr(0, 4)) return t
            }
            return e
        }
        function o(e) {
            if (!e || e.length % 4 == 1) return !1;
            for (var t, i, a = 0, o = 0, r = ""; i = e.charAt(o++);) i = n.indexOf(i),
            ~i && (t = a % 4 ? 64 * t + i : i, a++ % 4) && (r += String.fromCharCode(255 & t >> (-2 * a & 6)));
            return r
        }
    }
    var a = {};
    vk_decryptor_main(text,a);
    return(a.audioUnmaskSource(text));
}
