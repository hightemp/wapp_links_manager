// import { tpl, fnAlertMessage } from "./lib.js"

function tpl(strings, ...keys) {
    return (function(...values) {
        let dict = values[values.length - 1] || {};
        let result = [strings[0]];
        keys.forEach(function(key, i) {
            let value = Number.isInteger(key) ? values[key] : dict[key];
            result.push(value, strings[i + 1]);
        });
        return result.join('');
    });
}

alert('1');
class AddLink {
    static sURL = ``

    static oURLs = {
        update_or_create: `ajax.php?method=update_or_create_link`,

        list_tree_categories: tpl`ajax.php?method=list_tree_categories&group_id=${0}`,
        list_groups: `ajax.php?method=list_groups`,
    }
    static oWindowTitles = {
        create: 'Новая ссылка',
        update: 'Редактировать сслыку'
    }
    static oEvents = {
    }

    static get oEditDialogCategoryCleanBtn() {
        return $('#add-link-category-clean-btn');
    }
    static get oEditDialogNoteCleanBtn() {
        return $('#add-link-group-clean-btn');
    }
    static get oEditDialogSaveBtn() {
        return $('#add-link-save-btn');
    }
    static get oEditDialogCancelBtn() {
        return $('#add-link-cancel-btn');
    }

    static get oForm() {
        return $('#add-link-fm');
    }

    static get oCategoryTreeList() {
        return $("#add-link-category_id");
    }
    static get oGroupList() {
        return $("#add-link-group_id");
    }

    static fnReload() {
        this.fnComponent('reload');
    }

    static fnSave() {
        this.oForm.form('submit', {
            url: this.oURLs.update_or_create,
            iframe: false,
            onSubmit: (() => {
                return this.oForm.form('validate');
            }).bind(this),
            success: ((result) => {
                this.fnClear();
            }).bind(this)
        });
    }

    static fnClear() {
        this.oForm[0].reset();
    }

    static fnReloadLists()
    {
        this.oGroupList.combotree('reload');
        this.oGroupList.combotree('setValue', null);
        this.oCategoryTreeList.combotree('reload');
        this.oCategoryTreeList.combotree('setValue', null);
    }

    static fnBindEvents()
    {
        this.oEditDialogCategoryCleanBtn.click((() => {
            this.oCategoryTreeList.combotree('clear');
        }).bind(this))
        this.oEditDialogNoteCleanBtn.click((() => {
            this.oGroupList.combobox('clear');
        }).bind(this))
        this.oEditDialogSaveBtn.click((() => {
            console.log('oEditDialogSaveBtn');
            this.fnSave();
        }).bind(this))
        this.oEditDialogCancelBtn.click((() => {
            this.fnClear();
        }).bind(this))
    }

    static fnInitComponentCategoryTreeList()
    {
        this.oCategoryTreeList.combotree({
            url: this.oURLs.list_tree_categories(0),

            method: 'get',
            labelPosition: 'top',
            width: '100%',

            onLoadSuccess: ((node, data) => {

            }).bind(this),
        })
    }

    static fnInitComponentGroupsList()
    {
        this.oGroupList.combobox({
            url: this.oURLs.list_groups,

            method: 'get',
            valueField: 'id',
            textField: 'name',
            labelPosition: 'top',
            width: '100%',

            onChange: ((newValue, oldValue) => {
                var iID = this.oGroupList.combobox('getValue');
                var sURL = this.oURLs.list_tree_categories(iID);
                this.oCategoryTreeList.combotree('reload', sURL);
            }).bind(this),
        })
    }

    static fnInitComponent()
    {
        console.log('fnInitComponent');
        $("#add-link-url").textbox({
            label:'URL:',required:true,validType:'url',labelPosition: 'top'
        })
        $("#add-link-name").textbox({
            label:'Название:',required:true,labelPosition: 'top'
        })
        $("#add-link-description").textbox({
            label:'Сообщение:',multiline:true,labelPosition: 'top'
        })
    }

    static fnPrepare()
    {
        this.fnInitComponentCategoryTreeList();
        this.fnInitComponentGroupsList();
        this.fnInitComponent();
        this.fnBindEvents();
    }
}

alert('2');
AddLink.fnPrepare();
alert('3');