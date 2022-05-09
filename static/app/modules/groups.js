import { tpl, fnAlertMessage } from "./lib.js"

export class Groups {
    static sURL = ``

    static _oSelected = null;
    
    static oURLs = {
        create: 'ajax.php?method=create_group',
        update: tpl`ajax.php?method=update_group&id=${0}`,
        delete: 'ajax.php?method=delete_group',
        list: `ajax.php?method=list_groups`,
    }
    static oWindowTitles = {
        create: 'Новая группа',
        update: 'Редактировать группу'
    }
    static oEvents = {
        groups_save: "groups:save",
        groups_select: "groups:select",
        categories_save: "categories:save",
    }

    static get oDialog() {
        return $('#groups-dlg');
    }
    static get oDialogForm() {
        return $('#groups-dlg-fm');
    }
    static get oComponent() {
        return $("#group-list");
    }
    static get oContextMenu() {
        return $("#groups-mm");
    }

    static get oEditDialogCategoryCleanBtn() {
        return $('#groups-group-clean-btn');
    }
    static get oEditDialogSaveBtn() {
        return $('#groups-dlg-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#groups-dlg-cancel-btn');
    }

    static get oPanelAddButton() {
        return $('#groups-add-btn');
    }
    static get oPanelEditButton() {
        return $('#groups-edit-btn');
    }
    static get oPanelRemoveButton() {
        return $('#groups-remove-btn');
    }
    static get oPanelReloadButton() {
        return $('#groups-reload-btn');
    }

    static get fnComponent() {
        return this.oComponent.datalist.bind(this.oComponent);
    }

    static get oSelectedCategory() {
        return this._oSelected;
    }

    static fnShowDialog(sTitle) {
        this.oDialog.dialog('open').dialog('center').dialog('setTitle', sTitle);
    }
    static fnDialogFormLoad(oRows={}) {
        this.oDialogForm.form('clear');
        this.oDialogForm.form('load', oRows);
    }

    static fnShowCreateWindow() {
        this.sURL = this.oURLs.create;
        var oData = {}
        this.fnShowDialog(this.oWindowTitles.create);
        this.fnDialogFormLoad(oData);
    }

    static fnShowEditWindow(oRow) {
        if (oRow) {
            this.sURL = this.oURLs.update(oRow.id);
            this.fnShowDialog(this.oWindowTitles.update);
            this.fnDialogFormLoad(oRow);
        }
    }

    static fnReload() {
        this.fnComponent('reload');
    }

    static fnSave() {
        this.oDialogForm.form('submit', {
            url: this.sURL,
            iframe: false,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: (function(result){
                this.oDialog.dialog('close');
                this.fnReload();
                this.fnReloadLists();

                this.fnFireEvent_Save();
            }).bind(this)
        });
    }

    static fnDelete(oRow) {
        if (oRow){
            $.messager.confirm(
                'Confirm',
                'Удалить?',
                (function(r) {
                    if (r) {
                        $.post(
                            this.oURLs.delete,
                            { id: oRow.id },
                            (function(result) {
                                this.fnReload();
                            }).bind(this),
                            'json'
                        );
                    }
                }).bind(this)
            );
        }
    }

    static fnGetSelected() {
        return this.fnComponent('getSelected');
    }

    static fnSelect(oTarget) {
        this.fnComponent('select', oTarget);
    }

    static fnReloadLists()
    {
        
    }

    static fnBindEvents()
    {
        $(document).on(this.oEvents.groups_select, ((oEvent, oNode) => {
            this.fnReloadLists();
        }).bind(this))

        $(document).on(this.oEvents.categories_save, ((oEvent, oNode) => {
            this.fnReload();
        }).bind(this))

        this.oEditDialogSaveBtn.click((() => {
            this.fnSave();
        }).bind(this))
        this.oEditDialogCancelBtn.click((() => {
            this.oDialog.dialog('close');
        }).bind(this))

        this.oPanelAddButton.click((() => {
            this.fnShowCreateWindow();
        }).bind(this))
        this.oPanelEditButton.click((() => {
            this.fnShowEditWindow(this.fnGetSelected());
        }).bind(this))
        this.oPanelRemoveButton.click((() => {
            this.fnDelete(this.fnGetSelected());
        }).bind(this))
        this.oPanelReloadButton.click((() => {
            this.fnReload();
        }).bind(this))
    }

    static fnFireEvent_Save() {
        $(document).trigger(this.oEvents.groups_save);
    }

    static fnFireEvent_Select(oNode) {
        $(document).trigger(this.oEvents.groups_select, [oNode])
    }

    static fnInitComponent()
    {
        this.fnComponent({
            url: this.oURLs.list,
            method:'get',

            fit: true,
            border: false,

            toolbar: '#groups-tt',

            onSelect: ((iIndex, oNode) => {
                this._oSelected = oNode;
                this.fnFireEvent_Select(oNode);
            }).bind(this),

            onContextMenu: (function(e, node) {
                e.preventDefault();
                this.fnSelect(node.target);
                this.oContextMenu.menu('show', {
                    left: e.pageX,
                    top: e.pageY,
                    onClick: ((item) => {
                        if (item.id == 'create_group') {
                            this.fnShowCreateWindow();
                        }
                        if (item.id == 'edit') {
                            this.fnShowEditWindow(node);
                        }
                        if (item.id == 'delete') {
                            this.fnDelete(node);
                        }
                    }).bind(this)
                });
            }).bind(this),
            textFormatter: function(value,row,index) {
                var s = row.text;
                s += '&nbsp;<span style=\'color:blue\'>(' + row.count + ')</span>';
                return s;
            }
        })
    }

    static fnPrepare()
    {
        this.fnInitComponent()
        this.fnBindEvents();
    }
}