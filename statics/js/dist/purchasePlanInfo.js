var api = frameElement.api,
    curId = api.data.id,
    SYSTEM = system = parent.SYSTEM,
    THISPAGE = {
        init: function() {
            this.initDom();
            this.loadGrid();
            this.addEvent();
            $("#grid").jqGrid("setGridParam", {
                cellEdit: !0
            });
        },
        initDom: function() {
            this.$_matchCon = $("#matchCon");
            api.data.text ? this.$_matchCon.val(api.data.text) : this.$_matchCon.placeholder();
            this.goodsCombo = this.$_matchCon.combo({
                data: function() {
                    var e = Public.getDefaultPage();
                    return e.SYSTEM.goodsInfo
                },
                formatText: function(e) {
                    return "" === e.spec ? e.number + " " + e.name : e.number + " " + e.name + "_" + e.spec
                },
                value: "id",
                defaultSelected: ["id", api.data.id],
                editable: !0,
                maxListWidth: 500,
                cache: !1,
                forceSelection: !0,
                maxFilter: 10,
                trigger: !1,
                listHeight: 182,
                listWrapCls: "ui-droplist-wrap",
                callback: {
                    onChange: function(e) {
                        if (e && e.id) {
                            curId = e.id;
                            THISPAGE.reloadData()
                        }
                    }
                },
                queryDelay: 0,
                inputCls: "edit_subject",
                wrapCls: "edit_subject_wrap",
                focusCls: "",
                disabledCls: "",
                activeCls: ""
            }).getCombo()
        },
        loadGrid: function() {
            $(window).height() - $(".grid-wrap").offset().top - 84;
            $("#grid").jqGrid({
                //url: "/scm/invSa.do?action=justIntimeInv&invId=" + curId,
                url: invpu_purchasePlanInfo+"?id=" + curId,
                datatype: "json",
                width: 500,
                height: 264,
                altRows: !0,
                gridview: !0,
                colModel: [ {
                    name: "BOMName",
                    label: "物品名称",
                    width: 170,
                    title: !1,
                    align: "center"
                },{
                    name: "BOMModel",
                    label: "型号",
                    width: 170,
                    title: !1,
                    align: "center"
                },
                    {
                        name: "BOM_Accountt",
                        label: "数量",
                        width: 80,
                        title: !1,
                        align: "center"
                    },
                    {
                        name: "unitName",
                        label: "单位",
                        width: 50,
                        title: !1,
                        align: "center"
                    }],
                cmTemplate: {
                    sortable: !1
                },
                page: 1,
                sortname: "number",
                sortorder: "desc",
                pager: "#page",
                rowNum: 2e3,
                rowList: [300, 500, 1e3],
                scroll: 1,
                loadonce: !0,
                viewrecords: !0,
                shrinkToFit: !0,
                forceFit: !1,
                jsonReader: {
                    root: "data.rows",
                    records: "data.total",
                    repeatitems: !1,
                    id: 0
                },
                loadError: function() {}
            });
        },
        reloadData: function() {
            $("#grid").jqGrid("setGridParam", {
                //url: "/scm/invSa.do?action=justIntimeInv&invId=" + curId,
                url: invsa_infoDetail+"?id=" + curId,
                datatype: "json"
            }).trigger("reloadGrid")
        },
        addEvent: function() {
            var e = this;
            $("#search").click(function() {
                curId = e.goodsCombo.getValue();
                curId && THISPAGE.reloadData()
            });
            $("#refresh").click(function() {
                THISPAGE.reloadData()
            });
            this.$_matchCon.bind("focus", function() {
                var e = this;
                $(this).val() && setTimeout(function() {
                    e.select()
                }, 10)
            });

        }
    };
THISPAGE.init();