!function(e, o, a) {
function n(e, o, n) {
function r() {
i("Failed to contact remote API"), c = null;
}
function u() {
c && (clearTimeout(c), c = null);
}
var c = setTimeout(r, 3e3);
return i(""), a.ajax({
url: s.apiUrl + "/" + e + "/" + o + ".jsonp?version=" + encodeURIComponent(n),
dataType: "jsonp",
success: function(e, o, n) {
if (c) {
u();
var t = e && e.exact;
o = e && e.status;
t ? function(e) {
d["json-content"].value = e, a("#loco-remote-empty").hide(), a("#loco-remote-found").show();
}(t) : 404 === o ? i("Sorry, we don't know a bundle by this name") : (l.notices.error(e.error || "Unknown server error"), 
r());
}
},
error: function() {
c && (u(), r());
},
cache: !0
}), {
abort: u
};
}
function i(e) {
d["json-content"].value = "", a("#loco-remote-empty").show().find("span").text(e), 
a("#loco-remote-found").hide().removeClass("jshide");
}
var t, l = e.loco, s = l.conf || {}, d = o.getElementById("loco-remote");
a(d).find('button[type="button"]').on("click", function(e) {
return e.preventDefault(), t && t.abort(), t = n(d.vendor.value, d.slug.value, d.version.value), 
!1;
}), a(d).find('input[type="reset"]').on("click", function(e) {
return e.preventDefault(), i(""), !1;
}), a.ajax({
url: s.apiUrl + "/vendors.jsonp",
dataType: "jsonp",
success: function(e) {
for (var o, n, t = -1, r = e.length, u = a(d.vendor).html(""); ++t < r; ) o = e[t][0], 
n = e[t][1], u.append(a("<option></option>").attr("value", o).text(n));
},
cache: !0
});
}(window, document, jQuery);