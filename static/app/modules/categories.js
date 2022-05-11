import { tpl, fnAlertMessage } from "./lib.js"

export class Categories {
    static sURL = ``

    static _oSelected = null;
    static _oSelectedGroup = null;
    
    static oURLs = {
        create: 'ajax.php?method=create_category',
        update: tpl`ajax.php?method=update_category&id=${0}`,
        delete: 'ajax.php?method=delete_category',
        list: tpl`ajax.php?method=list_tree_categories&group_id=${0}`,

        move_to_root_category: 'ajax.php?method=move_to_root_category',

        list_tree_categories: tpl`ajax.php?method=list_tree_categories&group_id=${0}`,
        list_groups: `ajax.php?method=list_groups`,
    }
    static oWindowTitles = {
        create: 'Новая категория',
        update: 'Редактировать категория'
    }
    static oEvents = {
        categories_save: "categories:save",
        categories_select: "categories:select",
        groups_save: "groups:save",
        groups_select: "groups:select",
        links_add: "links:add",
        links_save: "links:save",
    }

    static get oDialog() {
        return $('#categories-dlg');
    }
    static get oDialogForm() {
        return $('#categories-dlg-fm');
    }
    static get oComponent() {
        return $("#categories-tree");
    }
    static get oContextMenu() {
        return $("#categories-mm");
    }

    static get oGroupList() {
        return $("#categories-group_id");
    }    
    static get oCategoryTreeList() {
        return $("#categories-category_id");
    }

    static get oEditDialogCategoryCleanBtn() {
        return $('#categories-category-clean-btn');
    }
    static get oEditDialogSaveBtn() {
        return $('#categories-dlg-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#categories-dlg-cancel-btn');
    }

    static get oPanelAddButton() {
        return $('#categories-add-btn');
    }
    static get oPanelEditButton() {
        return $('#categories-edit-btn');
    }
    static get oPanelRemoveButton() {
        return $('#categories-remove-btn');
    }
    static get oPanelReloadButton() {
        return $('#categories-reload-btn');
    }

    static get fnComponent() {
        return this.oComponent.treegrid.bind(this.oComponent);
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
        if (!this._oSelectedGroup) {
            return;
        }
        this.sURL = this.oURLs.create;
        var oData = {
            group_id: this._oSelectedGroup.id,
            category_id: this._oSelected ? this._oSelected.id : null,
        }
        this.fnShowDialog(this.oWindowTitles.create);
        this.fnDialogFormLoad(oData);

        this.oCategoryTreeList.combotree(
            'reload', 
            this.oURLs.list_tree_categories(this._oSelectedGroup.id)
        );
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

    static fnMoveToRoot(oRow) {
        if (oRow){
            $.post(
                this.oURLs.move_to_root_category,
                { id: oRow.id },
                (function(result) {
                    this.fnReload();
                    this.fnReloadLists();
                }).bind(this),
                'json'
            );
        }
    }

    static fnGetSelected() {
        return this.fnComponent('getSelected');
    }

    static fnSelect(oTarget) {
        this.fnComponent('select', oTarget);
    }

    static fnReloadLists() {
        this.oGroupList.combobox('reload');
        this.oCategoryTreeList.combotree('reload');
    }

    static fnBindEvents()
    {
        $(document).on(this.oEvents.links_save, ((oEvent, oNode) => {
            this.fnReload();
        }).bind(this))

        $(document).on(this.oEvents.groups_select, ((oEvent, oNode) => {
            this._oSelectedGroup = oNode;
            this._oSelected = null;
            this.oCategoryTreeList.combotree('setValue', null);
            this.fnInitComponent();
        }).bind(this))

        $(document).on(this.oEvents.groups_save, ((oEvent, oNode) => {
            this.fnReloadLists();
        }).bind(this))

        $(document).on(this.oEvents.categories_select, ((oEvent, oNode) => {
            this._oSelected = oNode;
            this.fnReloadLists();
        }).bind(this))

        this.oEditDialogCategoryCleanBtn.click((() => {
            this.oCategoryTreeList.combotree('clear');
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
        $(document).trigger(this.oEvents.categories_save);
    }

    static fnFireEvent_Select(oNode) {
        $(document).trigger(this.oEvents.categories_select, [oNode])
    }

    static fnFireEvent_LinksAdd() {
        $(document).trigger(this.oEvents.links_add);
    }

    static fnInitComponentGroupList()
    {
        this.oGroupList.combobox({
            url: this.oURLs.list_groups,
            method: 'get',
            valueField: 'id',
            textField: 'text',
            labelPosition: 'top',
            width: '100%',

            onClick: (() => {
                this.oCategoryTreeList.combotree('setValue', null);
                this.oCategoryTreeList.combotree(
                    'reload', 
                    this.oURLs.list_tree_categories(this.oGroupList.combobox('getValue'))
                );
            }).bind(this),

            onChange: (() => {
                this.oCategoryTreeList.combotree(
                    'reload', 
                    this.oURLs.list_tree_categories(this.oGroupList.combobox('getValue'))
                );
            }).bind(this),
        })
    }

    static fnInitComponentCategoryTreeList()
    {
        this.oCategoryTreeList.combotree({
            url: '', 
            method: 'get',
            valueField: 'id',
            textField: 'text',
            labelPosition: 'top',
            width: '100%',
        })
    }

    static fnInitComponent()
    {
        this.fnComponent({
            url: this._oSelectedGroup ? this.oURLs.list(this._oSelectedGroup.id) : 'ajax.php',
            method:'get',

            fit: true,
            border: false,

            nowrap: false,

            toolbar: '#categories-tt',

            idField:'id',
            treeField:'name',
            columns:[[
                {
                    title:'name',field:'name',width:400,
                    formatter: function(value,row,index) {
                        var s = row.text;
                        if (!row.count) {
                            s = `<b>${s}</b>`;
                        }
                        s += '&nbsp;<span style=\'color:blue\'>(' + row.count + ')</span>';
                        return s;
                    }
                },
            ]],

            onSelect: ((oNode) => {
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
                        if (item.id == 'add') {
                            this.fnShowCreateWindow();
                        }
                        if (item.id == 'add_link') {
                            this.fnFireEvent_LinksAdd();
                        }
                        if (item.id == 'edit') {
                            this.fnShowEditWindow(node);
                        }
                        if (item.id == 'delete') {
                            this.fnDelete(node);
                        }
                        if (item.id == 'move_to_root_category') {
                            this.fnMoveToRoot(node);
                        }
                    }).bind(this)
                });
            }).bind(this),

        })
    }

    static fnPrepare()
    {
        this.fnInitComponentCategoryTreeList();
        this.fnInitComponentGroupList();
        this.fnInitComponent()
        this.fnBindEvents();
    }
}