import { tpl, fnAlertMessage } from "./lib.js"

export class Files {
    static sURL = ``

    static oURLs = {
        create: 'ajax.php?method=create_file',
        update: tpl`ajax.php?method=update_file&id=${0}`,
        delete: 'ajax.php?method=delete_file',
        list: `ajax.php?method=list_files_paged`,
    }
    static oWindowTitles = {
        create: 'Новая',
        update: 'Редактировать'
    }
    static oEvents = {
        files_save: "files:save",
        files_item_click: "files:item_click",
    }

    static _oSelectedCategory = null;
    static _oSelectedRow = null;

    static get sTodoListToolbar() {
        return `#files-list-tb`;
    }

    static get oDialog() {
        return $('#files-dlg');
    }
    static get oDialogForm() {
        return $('#files-dlg-fm');
    }

    static get oComponent() {
        return $("#files-datagrid");
    }
    static get oContextMenu() {
        return $("#files-mm");
    }

    static get oCategoryTreeList() {
        return $("#files-category_id");
    }

    static get oEditDialogCategoryCleanBtn() {
        return $('#files-category-clean-btn');
    }
    static get oEditDialogParentTaskCleanBtn() {
        return $('#files-file-clean-btn');
    }
    static get oEditDialogSaveBtn() {
        return $('#files-dlg-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#files-dlg-cancel-btn');
    }


    static get oPanelUnselectButton() {
        return $('#files-unselect-btn');
    }
    static get oPanelAddButton() {
        return $('#files-add-btn');
    }
    static get oPanelEditButton() {
        return $('#files-edit-btn');
    }
    static get oPanelRemoveButton() {
        return $('#files-remove-btn');
    }
    static get oPanelReloadButton() {
        return $('#files-reload-btn');
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
        this.fnComponent('reload');
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
        $(document).on(this.oEvents.categories_select, ((oEvent, oNode) => {

        }).bind(this))

        $(document).on(this.oEvents.categories_save, ((oEvent, oNode) => {
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

        
        this.oPanelUnselectButton.click((() => {
            this._oSelectedRow = null;
            this.fnComponent('unselectAll');
        }).bind(this))
        this.oPanelAddButton.click((() => {
            this._oSelectedRow = this.fnGetSelected();

            this.fnShowCreateWindow();
        }).bind(this))
        this.oPanelEditButton.click((() => {
            var oSelected = this._oSelectedRow = this.fnGetSelected();

            if (oSelected) {
                this.fnShowEditWindow(oSelected);
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
        $(document).trigger(this.oEvents.files_save);
    }

    static fnFireEvent_ItemClick(oRow) {
        $(document).trigger(this.oEvents.files_item_click, [ oRow ]);
    }

    static fnInitComponent()
    {
        var iCID = this._oSelectedCategory ? this._oSelectedCategory.id : 0;

        this.fnComponent({
            singleSelect: true,

            fit: true,

            url: this.oURLs.list,
            method: 'get',

            width: '100%',
            height: "100%",
            rownumbers: true,
            pagination: true,

            clientPaging: false,
            remoteFilter: true,

            nowrap: false,

            pageSize: 24,
            pageList: [24, 25, 30, 40, 50, 60, 70, 80, 90, 100],

            idField: 'id',

            toolbar: '#files-tt',

            columns:[[
                {field:'created_at',title:'Создано',width:122},
                {
                    field:'filename',title:'Изображение',
                    width: 200,
                    formatter: function(value,row,index){
                        return `<img src="/uploads/images/${row.filename}" style="object-fit:contain; width:200px"/>`
                    }
                },
                {
                    field:'count',title:'Кол.',
                    width: 40
                },
                {
                    field:'name',title:'Название',
                    width:800
                },
            ]],

            onSelect: ((iIndex, oNode) => {
                this._oSelectedRow = this.fnGetSelected();
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
                            this.fnShowCreateWindow();
                        }
                        if (item.id == 'edit') {
                            this.fnShowEditWindow(node);
                        }
                        if (item.id == 'delete') {
                            this.fnDelete(node);
                        }
                    }
                });
            }).bind(this),

            rowStyler: function(index,row) {
                if (!row.count){
                    return 'font-weight:bold';
                }
            }

        });

        this.fnComponent('enableFilter', []);
    }

    static fnPrepare()
    {
        this.fnInitComponent();
        this.fnBindEvents();
    }
}