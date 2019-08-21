function init() {
	(12 == parent.SYSTEM.serviceType || 16 == parent.SYSTEM.serviceType) && $("#localRecover").hide();
	getBackupData();
	$("#start-backup").on("click", function(e) {
		e.preventDefault();
		Business.verifyRight("51") && backupConfirm()
	});
	$("#localRecover").on("click", function(e) {
		if (12 !== parent.SYSTEM.serviceType || 16 !== parent.SYSTEM.serviceType) {
			e.preventDefault();
			if (!Business.verifyRight("52")) return;
			recoverLocalBackup()
		}
	});
	$("#backup-status").on("click", ".btn-recover", function(e) {
		e.preventDefault();
		if (Business.verifyRight("52")) {
			var t = $(this).parent().data("id");
			recoverConfirm(function() {
				recoverBackup(t)
			})
		}
	});
	$("#backup-status").on("click", ".btn-del", function(e) {
		e.preventDefault();
		if (Business.verifyRight("53")) {
			var t = $(this).parent().data("id");
			deleteBackupConfirm(t)
		}
	})
}
function getBackupData() {
	$.ajax({
		type: "POST",
		dataType: "json",
		//url: "/scm/backup.do?m=queryBackupFile",
		url: backup_lists,
		success: function(e) {
			e && e.data && (BACKUP_DATA = e.data.items);
			BACKUP_DATA.length > 0 ? showBackup() : showNoBackup()
		},
		error: function() {
			parent.Public.tips({
				type: 1,
				content: "获取用户备份情况失败，请刷新页面重试！",
				autoClose: !1
			})
		}
	})
}
function showNoBackup() {
	var e = "<h3>温馨提示：</h3><p>您还没有备份记录，请点击“开始备份”按钮备份您的数据。</p>";
	$("#backup-status").html(e)
}
function showBackup() {
	var e = '<div id="dataGrid"><table id="grid"></table></div>';
	$("#backup-status").html(e);
	initGrid()
}
function initGrid() {
	var e = [400, 150, 120, 90];
	$("#grid").jqGrid({
		data: BACKUP_DATA,
		datatype: "local",
		height: Public.setGrid().h,
		autowidth: !0,
		shrinkToFit: !1,
		altRows: !0,
		gridview: !0,
		colModel: [{
			name: "operate",
			label: "操作",
			index: "operate",
			width: e[3],
			align: "center",
			title: !1,
			formatter: opeFmatter
		}, {
			name: "filename",
			label: "备份名称",
			index: "filename",
			width: e[0],
			title: !1
		}, {
			name: "createTime",
			label: "时间",
			index: "createTime",
			width: e[1],
			align: "center",
			title: !1
		}, {
			name: "size",
			label: "文件大小",
			index: "size",
			width: e[2],
			align: "center",
			title: !1,
			formatter: sizeFormatter
		}],
		cmTemplate: {
			sortable: !1
		},
		viewrecords: !0,
		localReader: {
			repeatitems: !1,
			id: "fid"
		}
	})
}
function nameFormatter(e) {
	return e.substr(e.lastIndexOf("/") + 1)
}
function sizeFormatter(e) {
	e = parseInt(e);
	e = isNaN(e) ? 0 : e;
	return Math.round(e / 1024) + " KB"
}
function opeFmatter(e, t, i) {
	return '<p data-id="' + i.fid + '" class="operate-wrap operating"><a class="btn-recover ui-icon ui-icon-copy" href="#" title="恢复">恢复</a><a class="btn-download ui-icon ui-icon-arrowthickstop-1-s" href="'+ backup_down +'?name=' + i.fid + '" target="_blank" title="下载">下载</a><a class="btn-del ui-icon ui-icon-trash" href="#" title="删除">删除</a></p>'
}
function backupConfirm() {
	var e = ["<p>为保证备份数据的完整性，<strong>请确保账套里的其他用户已经退出系统</strong>。</p>", "<p>确定执行备份？</p>"];
	$.dialog({
		title: "开始备份",
		id: "backupDialog",
		width: 300,
		height: 80,
		icon: "confirm.gif",
		fixed: !0,
		lock: !0,
		resize: !1,
		parent: parent || null,
		ok: function() {
			window.setTimeout(function() {
				doBackup()
			}, 0);
			return !0
		},
		cancel: !0,
		content: e.join("")
	})
}
function doBackup() {
	var e = $.dialog.tips("正在备份，这将需要几分钟时间，请耐心等候...", 1e3, "loading.gif", !0).show();
	$.ajax({
		type: "POST",
		dataType: "json",
		//url: "/scm/backup.do?m=backup",
		url: backup_add,
		success: function(t) {
			e.close();
			if (200 == t.status) {
				parent.Public.tips({
					content: "备份完成！"
				});
				t = t.data;
				var i = BACKUP_DATA.length;
				BACKUP_DATA.push(t);
				if (0 == i) showBackup();
				else {
					$("#grid").jqGrid("addRowData", t.fid, t, "first");
					$("#" + t.fid).addClass("ui-state-add").siblings().removeClass("ui-state-add")
				}
			} else parent.Public.tips({
				type: 1,
				content: t.msg
			})
		},
		error: function() {
			e.close();
			parent.Public.tips({
				type: 1,
				content: "备份失败！请重试。"
			})
		}
	})
}
function deleteBackupConfirm(e) {
	$.dialog({
		title: "删除备份",
		width: 200,
		height: 80,
		icon: "confirm.gif",
		fixed: !0,
		lock: !0,
		resize: !1,
		parent: parent || null,
		ok: function() {
			doDelete(e);
			return !0
		},
		cancel: !0,
		content: "确定删除该备份？"
	})
}
function doDelete(e) {
	$.ajax({
		type: "POST",
		dataType: "json",
		//url: "/scm/backup.do?m=deleteBackupFile&id=" + e,
		url: backup_del+"?name=" + e,
		success: function(t) {
			if (200 == t.status) {
				$("#grid").jqGrid("delRowData", e);
				for (var i = 0, r = BACKUP_DATA.length; r > i; i++) {
					var a = BACKUP_DATA[i];
					if (a.fid == e) {
						BACKUP_DATA.splice(i, 1);
						break
					}
				}
				0 == BACKUP_DATA.length && showNoBackup();
				parent.Public.tips({
					content: "删除备份成功！"
				})
			} else parent.Public.tips({
				type: 1,
				content: t.msg
			})
		},
		error: function() {
			parent.Public.tips({
				type: 1,
				content: "删除备份失败！请重试。"
			})
		}
	})
}
function recoverConfirm(e) {
	var t = ["<p>您将把帐套数据恢复到备份文件所在的状态，<strong>此操作不可回退</strong>，请谨慎操作。</p>", "<p>为保证备份数据的完整性，<strong>请确保账套里的其他用户已经退出系统</strong>。</p>", "<p>确定恢复备份？</p>"];
	$.dialog({
		title: "恢复备份",
		width: 340,
		height: 120,
		icon: "confirm.gif",
		fixed: !0,
		lock: !0,
		resize: !1,
		ok: function() {
			window.setTimeout(function() {
				e()
			}, 0);
			return !0
		},
		cancel: !0,
		content: t.join("")
	})
}
function recoverBackup(e) {
	var t = $.dialog.tips("正在恢复备份，这将需要几分钟时间，请耐心等候...", 1e3, "loading.gif", !0).show();
	$.ajax({
		type: "POST",
		dataType: "json",
		//url: "/scm/backup.do?m=recover&id=" + e,
		url: backup_recovery+"?name=" + e,
		success: function(e) {
			t.close();
			if (200 == e.status) {
				parent.window.$.cookie("ReloadTips", "恢复备份成功！");
				//parent.window.location.href = "../user/start?siId=" + parent.SYSTEM.DBID
			} else parent.Public.tips({
				type: 1,
				content: e.msg
			})
		},
		error: function() {
			t.close();
			parent.Public.tips({
				type: 1,
				content: "恢复备份失败！请重试。"
			})
		}
	})
}
function recoverLocalBackup() {
	$.dialog({
		title: "上传本地备份",
		id: "localBackupDialog",
		width: 460,
		height: 130,
		min: !1,
		max: !1,
		fixed: !0,
		resize: !1,
		lock: !0,
		content: "url:upload-backup.jsp",
		okVal: "上传",
		ok: function() {
			this.content.doUpload();
			return !1
		},
		cancel: !0
	})
}
var BACKUP_DATA = [];
init();
$(window).resize(function() {
	Public.resizeGrid()
});