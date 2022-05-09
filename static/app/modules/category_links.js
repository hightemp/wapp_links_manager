import { tpl, fnAlertMessage } from "./lib.js"

export class CategoryLinks {
    static sURL = ``

    static oURLs = {
        create: 'ajax.php?method=create_link',
        update: tpl`ajax.php?method=update_link&id=${0}`,
        delete: 'ajax.php?method=delete_link',
        list: tpl`ajax.php?method=list_links&category_id=${0}`,

        move_to_root_link: 'ajax.php?method=move_to_root_link',

        list_tree_categories: `ajax.php?method=list_tree_categories`,
        list_tree_links: tpl`ajax.php?method=list_tree_links&category_id=${0}`,
    }
    static oWindowTitles = {
        create: 'Новая задача',
        update: 'Редактировать задачу'
    }
    static oEvents = {
        links_save: "links:save",
        links_item_click: "links:item_click",
        links_create: "links:create",
        links_update: "links:update",
        category_links_save: "category_links:save",
        category_links_item_click: "category_links:item_click",
        categories_save: "categories:save",
        categories_select: "categories:select",
    }

    static _oSelectedCategory = null;
    static _oSelectedRow = null;

    static get oDialog() {
        return $('#links-dlg');
    }
    static get oDialogForm() {
        return $('#links-dlg-fm');
    }

    static get oComponent() {
        return $("#categories-link-datagrid");
    }
    static get oContextMenu() {
        return $("#category-links-mm");
    }

    static get oCategoryTreeList() {
        return $("#links-category_id");
    }

    static get oEditDialogCategoryCleanBtn() {
        return $('#links-category-clean-btn');
    }
    static get oEditDialogParentTaskCleanBtn() {
        return $('#links-link-clean-btn');
    }
    static get oEditDialogSaveBtn() {
        return $('#links-dlg-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#links-dlg-cancel-btn');
    }


    static get oPanelUnselectButton() {
        return $('#categories-link-unselect-btn');
    }
    static get oPanelAddButton() {
        return $('#categories-link-add-btn');
    }
    static get oPanelEditButton() {
        return $('#categories-link-edit-btn');
    }
    static get oPanelRemoveButton() {
        return $('#categories-link-remove-btn');
    }
    static get oPanelReloadButton() {
        return $('#categories-link-reload-btn');
    }

    static get fnComponent() {
        return this.oComponent.datagrid.bind(this.oComponent);
    }

    static fnShowDialog(sTitle) {
        this.oDialog.dialog('open').dialog('center').dialog('setTitle', sTitle);
    }
    static fnDialogFormLoad(oRows={}) {
        this.oDialogForm.form('clear');
        this.oDialogForm.form('load', oRows);
        this.oCategoryTreeList.combotree('setValue', oRows.category_id);
    }

    static fnShowCreateWindow() {
        this.sURL = this.oURLs.create;
        var oData = {
            category_id: this._oSelectedCategory ? this._oSelectedCategory.id : '',
            link_id: this._oSelectedRow ? this._oSelectedRow.id : '',
        }
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

    static fnGetSelected() {
        return this.fnComponent('getSelected');
    }

    static fnSelect(iID) {
        this.fnComponent('selectRecord', iID);
    }

    static fnReload() {
        if (this._oSelectedCategory) {
            this.fnComponent('reload', this.oURLs.list(this._oSelectedCategory.id));
        }
    }

    static fnSave() {
        this.oDialogForm.form('submit', {
            url: this.sURL,
            iframe: false,
            onSubmit: () => {
                return this.oDialogForm.form('validate');
            },
            success: ((result) => {
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
                ((r) => {
                    if (r) {
                        $.post(
                            this.oURLs.delete,
                            { id: oRow.id },
                            ((result) => {
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
                this.oURLs.move_to_root_link,
                { id: oRow.id },
                (function(result) {
                    this.fnReload();
                    this.fnReloadLists();
                }).bind(this),
                'json'
            );
        }
    }

    static fnReloadLists()
    {
        var iCID = this._oSelectedCategory ? this._oSelectedCategory.id : 0;

        this.oCategoryTreeList.combotree('reload');
    }

    static fnShowMessageCategoryNotSelected()
    {
        alert("Категория не выбрана");
    }

    static fnBindEvents()
    {
        $(document).on(this.oEvents.links_save, ((oEvent, oNode) => {
            this.fnReload();
        }).bind(this))
        
        $(document).on(this.oEvents.categories_select, ((oEvent, oNode) => {
            this._oSelectedCategory = oNode;
            this.fnReload();
        }).bind(this))

        $(document).on(this.oEvents.categories_save, ((oEvent, oNode) => {
            this.fnReloadLists();
        }).bind(this))

        // this.oEditDialogCategoryCleanBtn.click((() => {
        //     this.oCategoryTreeList.combotree('clear');
        // }).bind(this))
        // this.oEditDialogSaveBtn.click((() => {
        //     this.fnSave();
        // }).bind(this))
        // this.oEditDialogCancelBtn.click((() => {
        //     this.oDialog.dialog('close');
        // }).bind(this))

        
        this.oPanelUnselectButton.click((() => {
            this._oSelectedRow = null;
            this.fnComponent('unselectAll');
        }).bind(this))
        this.oPanelAddButton.click((() => {
            this._oSelectedRow = this.fnGetSelected();

            // this.fnShowCreateWindow();
            this.fnFireEvent_Create();
        }).bind(this))
        this.oPanelEditButton.click((() => {
            var oSelected = this._oSelectedRow = this.fnGetSelected();

            if (oSelected) {
                // this.fnShowEditWindow(oSelected);
                this.fnFireEvent_Update(oSelected);
            }
        }).bind(this))
        this.oPanelRemoveButton.click((() => {
            var oSelected = this._oSelectedRow = this.fnGetSelected();
            if (oSelected) {
                this.fnDelete(oSelected);
            }
        }).bind(this))
        this.oPanelReloadButton.click((() => {
            this.fnReload();
        }).bind(this))

    }

    static fnFireEvent_Save() {
        $(document).trigger(this.oEvents.category_links_save);
        $(document).trigger(this.oEvents.links_save);
    }

    static fnFireEvent_ItemClick(oRow) {
        $(document).trigger(this.oEvents.category_links_item_click, [ oRow ]);
        $(document).trigger(this.oEvents.links_item_click, [ oRow ]);
    }

    static fnFireEvent_Create() {
        $(document).trigger(this.oEvents.links_create);
    }

    static fnFireEvent_Update(oRow) {
        $(document).trigger(this.oEvents.links_update, [ oRow ]);
    }

    static fnInitComponentCategoryTreeList()
    {
        this.oCategoryTreeList.combotree({
            url: this.oURLs.list_tree_categories,
            method: 'get',
            labelPosition: 'top',
            width: '100%',
            onLoadSuccess: ((node, data) => {
                if (this._oSelectedCategory) {
                    this.oCategoryTreeList.combotree('setValue', this._oSelectedCategory.id);
                }
            }).bind(this),
        })
    }

    static fnInitComponent()
    {
        var iCID = this._oSelectedCategory ? this._oSelectedCategory.id : 0;

        this.fnComponent({
            singleSelect: true,

            fit: true,

            url: 'ajax.php',
            method: 'get',

            width: "100%",
            height: "100%",
            rownumbers: true,
            pagination: true,

            remoteFilter: true,

            nowrap: false,

            pageSize: 25,
            pageList: [25, 30, 40, 50, 60, 70, 80, 90, 100],

            idField: 'id',

            toolbar: '#categories-link-tt',

            columns:[[
                {field:'created_at',title:'Создано',width:122},
                {
                    field:'name',title:'Название',
                    width:400
                },
                {
                    field:'url',title:'URL',
                    width:1200
                },
            ]],

            onSelect: ((iIndex, oNode) => {
                this._oSelectedRow = this.fnGetSelected();
            }),

            onDblClickRow: ((iIndex, oNode) => {
                window.open(oNode.url);
            }),

            onRowContextMenu: ((e, index, node) => {
                e.preventDefault();

                this.fnSelect(node.id);

                this._oSelectedRow = this.fnGetSelected();
                
                this.oContextMenu.menu('show', {
                    left: e.pageX,
                    top: e.pageY,
                    onClick: (item) => {
                        if (item.id == 'add') {
                            // this.fnShowCreateWindow();
                            this.fnFireEvent_Create();
                        }
                        if (item.id == 'edit') {
                            // this.fnShowEditWindow(node);
                            this.fnFireEvent_Update(node);
                        }
                        if (item.id == 'delete') {
                            this.fnDelete(node);
                        }
                    }
                });
            }).bind(this),
        });
    }

    static fnPrepare()
    {
        this.fnInitComponentCategoryTreeList();
        this.fnInitComponent();
        this.fnBindEvents();
    }
}