!
function(t) {
	//function e() {
//		t.ajax({
//			url: "/right.do?action=isMaxShareUser",
//			dataType: "json",
//			type: "POST",
//			success: function(t) {
//				if (200 == t.status) {
//					var e = t.data;
//					if (e.shareTotal >= e.totalUserNum) {
//						Public.tips({
//							type: 2,
//							content: "共享用户已经达到上限值：" + e.totalUserNum
//						});
//						return !1
//					}
//				}
//			}
//		})
//	}
	function i() {
		var e = t.trim(t("#userName").val()),
			i = t('<span class="loading"><i class="ui-incon ui-icon-loading"></i>检查用户名...</span>').insertAfter(t("#userName"));
		t("#registerForm").data("onPost", !0);
		t.ajax({
			//url: "/right.do?action=queryUserByName&userName=" + e,
			url: basedata_admin_checkname+"?userName=" + e,
			dataType: "json",
			type: "POST",
			success: function(e) {
				i.remove();
				t("#registerForm").data("onPost", !1);
				if (200 == e.status) {
					t("#userName").data("valid", !1);
					s(t("#userName"), !1, "该用户名已被占用")
				} else {
					t("#userName").data("valid", !0);
					s(t("#userName"), !0)
				}
			}
		})
	}
	function a() {
		var e = {
			name: t.trim(t("#realName").val()),
			username: t.trim(t("#userName").val()),
			mobile:  t.trim(t("#userMobile").val()),
			userpwd: t.trim(t("#password").val())
		};
		t("#registerForm").data("onPost", !0);
		t.ajax({
			//url: "/right.do?action=addUser",
			url: admin_edit,
			data: e,
			type: "POST",
			dataType: "json",
			success: function(i) {
				t("#registerForm").data("onPost", !1);
				200 == i.status && Public.tips({
					type: 0,
					content: i.msg
				})
				setTimeout('location.href="'+ admin_edit +'"',1000);
			},
			error: function() {
				t("#registerForm").data("onPost", !1);
				Public.tips({
					type: 1,
					content: "创建用户失败！请重试"
				})
			}
		})
	}
	function r(t) {
		var e, i = 0,
			a = t.length;
		/\d/.test(t) && i++;
		/[a-z]/.test(t) && i++;
		/[A-Z]/.test(t) && i++;
		/[^a-zA-Z0-9]/.test(t) && i++;
		6 > a ? e = 0 : a >= 6 && (e = i);
		return e
	}
	function n(t) {
		for (var e = t.find("input:visible"), i = !0, a = 0, r = e.length; r > a; a++) {
			var n = e.eq(a);
			if ("undefined" != typeof n.data("valid")) {
				if (!n.data("valid")) {
					i = !1;
					n.addClass("input-error")
				}
			} else o(n) || (i = !1)
		}
		return i
	}
	function o(e) {
		var i = e.attr("id"),
			a = d[i];
		if (a && a.required) {
			var r = t.trim(e.val());
			for (var n in a) {
				var o, l = !0;
				if ("min" == n) {
					var p = a[n];
					p > r.length && (l = !1)
				} else if ("max" == n) {
					var p = a[n];
					p < r.length && (l = !1)
				} else if ("length" == n) {
					var p = a[n];
					p != r.length && (l = !1)
				} else if ("equalTo" == n) {
					var h = t.trim(t(a[n]).val());
					r != h && (l = !1)
				} else if (u[n]) u[n].test(r) || (l = !1);
				else if ("required" == n) r || (l = !1);
				else if (t.isFunction(a[n])) {
					var f = a[n];
					l = f()
				} else if ("ajaxValid" == n) var g = a[n];
				if (!l) {
					o = c[i][n];
					s(e, l, o);
					e.data("valid", !1);
					return !1
				}
			}
			if (g) t.ajax({
				type: "POST",
				dataType: "json",
				url: g.url,
				success: function(t) {
					l = g.success(t);
					if (l) {
						s(e, l);
						e.data("valid", !0);
						return !0
					}
					o = c[i][n];
					s(e, l, o);
					e.data("valid", !1)
				}
			});
			else {
				s(e, l);
				e.data("valid", !0)
			}
			return !0
		}
	}
	function s(e, i, a) {
		var r = e.parent().find(".valid-msg");
		0 == r.length && (r = t('<span class="valid-msg"><i /><span /></span>').insertAfter(e));
		a = i ? "" : a;
		if (i) {
			r.addClass("valid-success").removeClass("valid-error");
			e.removeClass("input-error")
		} else {
			r.addClass("valid-error").removeClass("valid-success");
			e.addClass("input-error")
		}
		r.show().find(">span").text(a)
	}
	function l(t) {
		t.parent().find("span.valid-msg").hide();
		t.removeClass("input-error")
	}
	t(document).ready(function() {
		//e()
	});
	var d = {
		userName: {
			required: !0,
			min: 2,
			max: 20,
			userName: !0
		},
		password: {
			required: !0,
			min: 6,
			max: 20,
			notAllNum: !0,
			password: !0
		},
		pswConfirm: {
			required: !0,
			equalTo: "#password"
		},
		realName: {
			required: !0,
			realName: !0
		},
		userMobile: {
			required: !0,
			mobile: !0
		}
	},
		c = {
			userName: {
				required: "请输入用户名",
				min: "用户名长度应该为2-20位",
				max: "用户名长度应该为2-20位",
				userName: "用户名应该由英文字母或数字组成"
			},
			password: {
				required: "请输入密码",
				min: "密码长度应该为6-20位",
				max: "密码长度应该为6-20位",
				notAllNum: "密码不能全为数字",
				password: "密码应该由英文字母（区分大小写）或数字或特殊符号组成"
			},
			pswConfirm: {
				required: "请再次输入密码",
				equalTo: "两次输入的密码不一致"
			},
/*			realName: {
				required: "请输入真实姓名",
				realName: "请输入真实姓名"
			},
			userMobile: {
				required: "请输入常用手机",
				mobile: "请输入正确的手机号码"
			}*/
		},
		u = {
			userName: /^[A-Za-z\u4e00-\u9fa5]+$/,
			password: /^.*[A-Za-z0-9_-]+.*$/,
			mobile: /^(13|15|18)[0-9]{9}$/,
			notAllNum: /[^0-9]+/,
			realName: /^[A-Za-z\u4e00-\u9fa5]+$/
		};
	t("#registerForm input").on("focus", function() {
		t(this).parent().find(".msg").addClass("msg-focus");
		l(t(this));
		"password" == t(this).attr("id") && t("#pswStrength").show()
	}).on("blur", function() {
		if ("undefined" == typeof t(this).data("valid")) {
			o(t(this));
			if ("userName" == t(this).attr("id") && t(this).data("valid")) {
				t(this).removeData("valid");
				l(t(this));
				i()
			}
		} else {
			t(this).data("valid") === !1 && t(this).addClass("input-error");
			t(this).parent().find(".valid-msg").show()
		}
		t(this).parent().find(".msg").removeClass("msg-focus");
		"password" == t(this).attr("id") && t("#pswStrength").hide()
	}).on("change", function() {
		t(this).removeData("valid")
	});
	t("#registerBtn").on("click", function(e) {
		e.preventDefault();
		n(t("#registerForm")) && !t("#registerForm").data("onPost") && a()
	});
	t("#password").on("keyup", function() {
		var e = t.trim(t(this).val()),
			i = r(e);
		t("#pswStrength b").removeClass("on");
		t.each(t("#pswStrength b"), function(e, a) {
			e > i - 1 || t(a).addClass("on")
		});
		var a = "密码强度";
		1 == i ? a += "：弱" : 2 == i ? a += "：中" : 3 == i && (a += "：强");
		t("#pswStrength p").text(a)
	})
}(jQuery);