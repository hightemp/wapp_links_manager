import { tpl, fnAlertMessage } from "./lib.js"

export class Links {
    static sURL = ``

    static oURLs = {
        create: 'ajax.php?method=create_link',
        update: tpl`ajax.php?method=update_link&id=${0}`,
        delete: 'ajax.php?method=delete_link',
        list: `ajax.php?method=list_links`,

        move_to_root_link: 'ajax.php?method=move_to_root_link',

        list_tree_categories: tpl`ajax.php?method=list_tree_categories&group_id=${0}`,
        list_tree_links: tpl`ajax.php?method=list_tree_links&category_id=${0}`,
        list_notes: `ajax.php?method=list_all_notes`,
        list_groups: `ajax.php?method=list_groups`,
    }
    static oWindowTitles = {
        create: 'Новая ссылка',
        update: 'Редактировать сслыку'
    }
    static oEvents = {
        links_save: "links:save",
        links_item_click: "links:item_click",
        links_add: "links:add",
        links_create: "links:create",
        links_update: "links:update",
        categories_save: "categories:save",
        categories_select: "categories:select",
    }

    static _oSelectedCategory = null;
    static _oSelectedRow = null;

    static get sTodoListToolbar() {
        return `#links-list-tb`;
    }

    static get oDialog() {
        return $('#links-dlg');
    }
    static get oDialogForm() {
        return $('#links-dlg-fm');
    }

    static get oComponent() {
        return $("#links-table");
    }
    static get oContextMenu() {
        return $("#links-mm");
    }

    static get oGroupsList() {
        return $("#links-group_id");
    }
    static get oCategoryTreeList() {
        return $("#links-category_id");
    }
    static get oNotesList() {
        return $("#links-note_id");
    }

    static get oEditDialogCategoryCleanBtn() {
        return $('#links-category-clean-btn');
    }
    static get oEditDialogNoteCleanBtn() {
        return $('#links-note-clean-btn');
    }
    static get oEditDialogSaveBtn() {
        return $('#links-dlg-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#links-dlg-cancel-btn');
    }


    static get oPanelUnselectButton() {
        return $('#link-unselect-btn');
    }
    static get oPanelAddButton() {
        return $('#link-add-btn');
    }
    static get oPanelEditButton() {
        return $('#link-edit-btn');
    }
    static get oPanelRemoveButton() {
        return $('#link-remove-btn');
    }
    static get oPanelReloadButton() {
        return $('#link-reload-btn');
    }

    static get fnComponent() {
        return this.oComponent.datagrid.bind(this.oComponent);
    }

    static fnShowDialog(sTitle) {
        this.oDialog.dialog('open').dialog('center').dialog('setTitle', sTitle);
    }
    static fnDialogFormLoad(oRows={}) {
        this._oSelectedRow = oRows
        this.oDialogForm.form('clear');
        this.oDialogForm.form('load', oRows);
        this.oCategoryTreeList.combotree('setValue', oRows.category_id);
    }

    static fnShowCreateWindow() {
        this.sURL = this.oURLs.create;
        var oData = {
            category_id: this._oSelectedCategory ? this._oSelectedCategory.id : '',
            group_id: this._oSelectedCategory ? this._oSelectedCategory.group_id : '',
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
        }).bind(this))

        $(document).on(this.oEvents.categories_save, ((oEvent, oNode) => {
            this.fnReloadLists();
        }).bind(this))

        $(document).on(this.oEvents.links_add, ((oEvent, oNode) => {
            this.fnShowCreateWindow();
        }).bind(this))

        $(document).on(this.oEvents.links_create, ((oEvent, oNode) => {
            this.fnShowCreateWindow();
        }).bind(this))
        $(document).on(this.oEvents.links_update, ((oEvent, oNode) => {
            this.fnShowEditWindow(oNode);
        }).bind(this))

        this.oEditDialogCategoryCleanBtn.click((() => {
            this.oCategoryTreeList.combotree('clear');
        }).bind(this))
        this.oEditDialogNoteCleanBtn.click((() => {
            this.oNotesList.combobox('clear');
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
        $(document).trigger(this.oEvents.links_save);
    }

    static fnFireEvent_ItemClick(oRow) {
        $(document).trigger(this.oEvents.links_item_click, [ oRow ]);
    }

    static fnInitComponentGroupList()
    {
        this.oGroupsList.combobox({
            url: this.oURLs.list_groups,
            method: 'get',
            labelPosition: 'top',
            width: '100%',

            valueField: 'id',
            textField: 'name',

            // onLoadSuccess: ((node, data) => {
            //     if (this._oSelectedRow) {
            //         this.oCategoryTreeList.combotree('setValue', this._oSelectedRow.group_id);
            //     }
            // }).bind(this),

            onChange: ((newValue, oldValue) => {
                this.oCategoryTreeList.combotree('setValue', null);
                this.oCategoryTreeList.combotree('reload', this.oURLs.list_tree_categories(newValue));
            }).bind(this),
        })
    }

    static fnInitComponentCategoryTreeList()
    {
        // var oSelected = this._oSelectedRow = this.fnGetSelected();
        var iGID = this._oSelectedRow ? this._oSelectedRow.group_id : 0;
        
        this.oCategoryTreeList.combotree({
            url: this.oURLs.list_tree_categories(iGID),
            method: 'get',
            labelPosition: 'top',
            width: '100%',

            rownumbers: true,
            pagination: true,
            border: false,

            remoteFilter: true,

            nowrap: false,
            editable: true,

            pageSize: 6,
            pageList: [6, 10, 24, 25, 30, 40, 50, 60, 70, 80, 90, 100],

            idField: 'id',
            treeField:'name',

            columns:[[
                {field:'name',title:'name',width:420},
            ]],

            onLoadSuccess: ((node, data) => {
                if (this._oSelectedRow) {
                    this.oCategoryTreeList.combotree('setValue', this._oSelectedRow.category_id);
                }
            }).bind(this),
        })

        // this.oCategoryTreeList.combotree('enableFilter', []);
    }

    static fnInitComponentNotesList()
    {
        this.oNotesList.combobox({
            url: this.oURLs.list_notes,
            method: 'get',
            labelPosition: 'top',
            width: '100%',

            valueField: 'id',
            textField: 'name',
        })
    }

    static editIndex = undefined;
    static endEditing(){
        if (this.editIndex == undefined){return true}
        if (this.fnComponent('validateRow', this.editIndex)){
            this.fnComponent('endEdit', this.editIndex);
            this.editIndex = undefined;
            return true;
        } else {
            return false;
        }
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
            border: false,

            clientPaging: false,
            remoteFilter: true,

            nowrap: false,

            pageSize: 24,
            pageList: [24, 25, 30, 40, 50, 60, 70, 80, 90, 100],

            idField: 'id',

            toolbar: '#link-tt',

            columns:[[
                {field:'created_at',title:'Создано',width:122},
                {
                    field:'group_id',title:'Группа',
                    width:250,
                    formatter: function(value,row,index){
                        return row.group_name;
                    }
                },
                {
                    field:'category_id',title:'Категория',
                    width:250,
                    editor:{
                        type:'combotree',
                        options: {
                            url: this.oURLs.list_tree_categories(0),
                            method: 'get',
                            labelPosition: 'top',
                            width: '100%',
                
                            rownumbers: true,
                            pagination: true,
                            border: false,
                
                            remoteFilter: true,
                
                            nowrap: false,
                            editable: true,
                
                            pageSize: 6,
                            pageList: [6, 10, 24, 25, 30, 40, 50, 60, 70, 80, 90, 100],
                
                            idField: 'id',
                            treeField:'name',
                
                            columns:[[
                                {field:'name',title:'name',width:420},
                            ]],
                        }
                    },
                    formatter: function(value,row,index){
                        return row.category_name;
                    }
                },
                {
                    field:'name',title:'Название',
                    width:400
                },
                {
                    field:'url',title:'URL',
                    width:1200
                },
            ]],

            onEndEdit: ((iIndex, oRow) => {
                $.post(this.oURLs.update(oRow.id), { ...oRow })
                    .done(() => { this.fnReload(); })
            }).bind(this), 

            onSelect: ((iIndex, oNode) => {
                this._oSelectedRow = this.fnGetSelected();
            }).bind(this),

            onDblClickRow: ((iIndex, oNode) => {
                window.open(oNode.url);
            }).bind(this),

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
                if (!row.category_id || !row.group_id){
                    return 'background-color:#EE0000;color:#fff;font-weight:bold';
                }
            }
        });

        this.fnComponent('enableFilter', []);
        this.fnComponent('enableCellEditing');
    }

    static fnPrepare()
    {
        this.fnInitComponentGroupList();
        this.fnInitComponentCategoryTreeList();
        this.fnInitComponentNotesList();
        this.fnInitComponent();
        this.fnBindEvents();
    }
}