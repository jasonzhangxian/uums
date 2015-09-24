// 自定义字段 - 所属部门字段
Ext.define("Uums.User.DepartmentEditor", {
    extend: "Ext.form.field.Trigger",
    alias: "widget.uums_department_editor",
    
    initComponent: function () {
        this.enableKeyEvents = true;

        this.callParent(arguments);

        this.on("keydown", function (field, e) {
            if (e.getKey() === e.BACKSPACE) {
                e.preventDefault();
                return false;
            }

            if (e.getKey() !== e.ENTER) {
                this.onTriggerClick(e);
            }
        });
    },

    onTriggerClick: function (e) {
        Ext.define("UumsDeparemtnEditor", {
            extend: "Ext.data.Model",
            fields: ["id", "text", "leaf", "children"]
        });

        var orgStore = Ext.create("Ext.data.TreeStore", {
            model: "UumsDeparemtnEditor",
            proxy: {
                type: "ajax",
                url: Uums.department_request_url,
                extraParams : {action:'tree'}
            }
        });

        var DptTree = Ext.create("Ext.tree.Panel", {
            store: orgStore,
            rootVisible: false,
            useArrows: true,
            viewConfig: {
                loadMask: true
            },
            columns: {
                defaults: {
                    flex: 1,
                    sortable: false,
                    menuDisabled: true,
                    draggable: false
                },
                items: [
                    {
                        xtype: "treecolumn",
                        text: "名称",
                        dataIndex: "text"
                    }
                ]
            }
        });
        DptTree.on("itemdblclick", this.onOK, this);
        this.tree = DptTree;

        var wnd = Ext.create("Ext.window.Window", {
            title: "选择所属部门",
            modal: true,
            width: 250,
            height: 380,
            layout: "fit",
            items: [DptTree],
            buttons: [
                {
                    text: "确定", handler: this.onOK, scope: this
                },
                {
                    text: "取消", handler: function () { wnd.close(); }
                }
            ]
        });
        this.wnd = wnd;
        wnd.show();
    },

    // private
    onOK: function () {
        var tree = this.tree;
        var item = tree.getSelectionModel().getSelection();

        if (item === null || item.length !== 1) {
            Ext.Msg.alert(UumsLanguage.msgErrTitle,"没有选择所属部门");

            return;
        }

        var data = item[0].data;
        // if (!data.leaf) {
        //     Ext.Msg.alert(UumsLanguage.msgErrTitle,"只能选择部门子节点");

        //     return;
        // }
        var parentItem = this.initialConfig.parentItem;
        this.focus();
        parentItem.setDepartment(data);
        this.wnd.close();
        this.focus();
    },

});