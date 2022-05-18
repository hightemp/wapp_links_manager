<div id="tt" class="easyui-tabs" style="width:100%;height:100%;">
    <div title="Ссылки" style="padding:0px;display:none;">
        <div style="width:100%;height:100%">
            <table id="links-table" class="easyui-datagrid" data-options="fit:true" style="width:100%;"></table>
            <div id="link-tt">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true" id="link-unselect-btn"></a>
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="link-add-btn"></a>
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="link-edit-btn"></a>
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="link-remove-btn"></a>
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="link-reload-btn"></a>
            </div>
        </div>
    </div>
    <div title="Категории" style="padding:0px;display:none;">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',split:true" title="" style="width:600px;">

                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'west',split:true" title="" style="width:200px;">

                        <ul id="group-list" class="easyui-datalist"></ul>
                        <div id="groups-tt">
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="groups-add-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="groups-edit-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="groups-remove-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="groups-reload-btn"></a>
                        </div>

                    </div>
                    <div data-options="region:'center',title:'',iconCls:'icon-ok'">

                        <ul id="categories-tree" class="easyui-treegrid"></ul>
                        <div id="categories-tt">
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="categories-add-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="categories-edit-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="categories-remove-btn"></a>
                            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="categories-reload-btn"></a>
                        </div>

                    </div>
                </div>
            </div>
            <div data-options="region:'center',title:'',iconCls:'icon-ok'">
                <table id="categories-link-datagrid" class="easyui-datagrid" data-options="fit:true"></table>
                <div id="categories-link-tt">
                    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true" id="categories-link-unselect-btn"></a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="categories-link-add-btn"></a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="categories-link-edit-btn"></a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="categories-link-remove-btn"></a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="categories-link-reload-btn"></a>
                </div>
            </div>
        </div>
    </div>
    <div title="Домены" style="padding:0px;display:none;">
        <table id="domains-datagrid" class="easyui-datagrid" data-options="fit:true"></table>
        <div id="domains-tt">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true" id="domains-unselect-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="domains-add-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="domains-edit-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="domains-remove-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="domains-reload-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" id="domains-scan-btn"></a>
        </div>
    </div>
    <div title="Заметки" style="padding:0px;display:none;">
        <table id="notes-datagrid" class="easyui-datagrid" data-options="fit:true"></table>
        <div id="notes-tt">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true" id="notes-unselect-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="notes-add-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="notes-edit-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="notes-remove-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="notes-reload-btn"></a>
        </div>
    </div>
    <div title="Файлы" style="padding:0px;display:none;">
        <table id="files-datagrid" class="easyui-datagrid" data-options="fit:true"></table>
        <div id="files-tt">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true" id="files-unselect-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="files-add-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="files-edit-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="files-remove-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="files-reload-btn"></a>
        </div>
    </div>
    <div title="Тэги" style="padding:0px;display:none;">
        <table id="tags-datagrid" class="easyui-datagrid" data-options="fit:true"></table>
        <div id="tags-tt">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true" id="tags-unselect-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" id="tags-add-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" id="tags-edit-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" id="tags-remove-btn"></a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" id="tags-reload-btn"></a>
        </div>
    </div>
</div>


<div style="position:fixed">
    <!-- Группы -->
    <div id="groups-dlg" class="easyui-dialog" style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#groups-dlg-buttons'">
        <form id="groups-dlg-fm" method="post" novalidate style="margin:0;padding:5px">
            <div style="margin-bottom:10px">
                <label>Заголовок:</label>
                <input name="name" class="easyui-textbox" required="true" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Описание:</label>
                <input name="description" class="easyui-textbox" style="width:100%;height:200px" multiline="true">
            </div>
        </form>
    </div>
    <div id="groups-dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="groups-dlg-save-btn" style="width:auto">Сохранить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" id="groups-dlg-cancel-btn" style="width:auto">Отмена</a>
    </div>

    <!-- Категории -->
    <div id="categories-dlg" class="easyui-dialog" style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#categories-dlg-buttons'">
        <form id="categories-dlg-fm" method="post" novalidate style="margin:0;padding:5px">
            <div style="margin-bottom:10px">
                <label>Группа:</label>
                <div class="input-with-btn">
                    <div><input id="categories-group_id" name="group_id" class="easyui-combobox" style="width:100%"></div>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-remove" id="categories-group-clean-btn" style="width:auto"></a>
                </div>
            </div>
            <div style="margin-bottom:10px">
                <label>Категория:</label>
                <div class="input-with-btn">
                    <div><input id="categories-category_id" name="category_id" class="easyui-combotree" style="width:100%"></div>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-remove" id="categories-category-clean-btn" style="width:auto"></a>
                </div>
            </div>
            <div style="margin-bottom:10px">
                <label>Заголовок:</label>
                <input name="name" class="easyui-textbox" required="true" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Описание:</label>
                <input name="description" class="easyui-textbox" style="width:100%;height:200px" multiline="true">
            </div>
        </form>
    </div>
    <div id="categories-dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="categories-dlg-save-btn" style="width:auto">Сохранить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" id="categories-dlg-cancel-btn" style="width:auto">Отмена</a>
    </div>

    <!-- Ссылки -->
    <div id="links-dlg" class="easyui-dialog" style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#links-dlg-buttons'">
        <form id="links-dlg-fm" method="post" novalidate style="margin:0;padding:5px">
            <div style="margin-bottom:10px">
                <label>Группа:</label>
                <div class="input-with-btn">
                    <div><input id="links-group_id" name="group_id" class="easyui-combobox" style="width:100%"></div>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-remove" id="links-group-clean-btn" style="width:auto"></a>
                </div>
            </div>
            <div style="margin-bottom:10px">
                <label>Категория:</label>
                <div class="input-with-btn">
                    <div><input id="links-category_id" name="category_id" class="easyui-combotreegrid" style="width:100%"></div>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-remove" id="links-category-clean-btn" style="width:auto"></a>
                </div>
            </div>
            <div style="margin-bottom:10px">
                <label>Ссылка на заметку:</label>
                <div class="input-with-btn">
                    <div><input id="links-note_id" name="note_id" class="easyui-combobox" style="width:100%"></div>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-remove" id="links-note-clean-btn" style="width:auto"></a>
                </div>
            </div>
            <div style="margin-bottom:10px" id="links-url-fieldblock">
                <label>URL:</label>
                <input name="url" class="easyui-textbox" style="width:100%;" id="links-url">
            </div>
            <div style="margin-bottom:10px" id="links-name-fieldblock">
                <label>Заголовок:</label>
                <input name="name" class="easyui-textbox" style="width:100%;" id="links-name">
            </div>
            <div style="margin-bottom:10px">
                <label>Описание:</label>
                <input name="description" class="easyui-textbox" style="width:100%;height:200px" multiline="true">
            </div>
        </form>
    </div>
    <div id="links-dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="links-dlg-save-btn" style="width:auto">Сохранить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" id="links-dlg-cancel-btn" style="width:auto">Отмена</a>
    </div>

    <!-- Заметки -->
    <div id="notes-dlg" class="easyui-dialog" style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#notes-dlg-buttons'">
        <form id="notes-dlg-fm" method="post" novalidate style="margin:0;padding:5px">
            <div style="margin-bottom:10px">
                <label>Заголовок:</label>
                <input name="name" class="easyui-textbox" required="true" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Описание:</label>
                <input name="description" class="easyui-textbox" style="width:100%;height:200px" multiline="true">
            </div>
        </form>
    </div>
    <div id="notes-dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="notes-dlg-save-btn" style="width:auto">Сохранить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" id="notes-dlg-cancel-btn" style="width:auto">Отмена</a>
    </div>

    <!-- Домены -->
    <div id="domains-dlg" class="easyui-dialog" style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#domains-dlg-buttons'">
        <form id="domains-dlg-fm" method="post" novalidate style="margin:0;padding:5px">
            <div style="margin-bottom:10px">
                <label>Заголовок:</label>
                <input name="name" class="easyui-textbox" required="true" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Описание:</label>
                <input name="description" class="easyui-textbox" style="width:100%;height:200px" multiline="true">
            </div>
        </form>
    </div>
    <div id="domains-dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="domains-dlg-save-btn" style="width:auto">Сохранить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" id="domains-dlg-cancel-btn" style="width:auto">Отмена</a>
    </div>

    <!-- Файлы -->
    <div id="files-dlg" class="easyui-dialog" style="width:500px" data-options="closed:true,modal:true,border:'thin',buttons:'#files-dlg-buttons'">
        <form id="files-dlg-fm" method="post" novalidate style="margin:0;padding:5px">
            <div style="margin-bottom:10px">
                <label>Заголовок:</label>
                <input name="name" class="easyui-textbox" required="true" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Описание:</label>
                <input name="description" class="easyui-textbox" style="width:100%;height:200px" multiline="true">
            </div>
            <div style="margin-bottom:10px">
                <label>Файл:</label>
                <input name="file" class="easyui-filebox" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <label>Путь:</label>
                <input name="filename" class="easyui-textbox" style="width:100%" readonly="true">
            </div>
        </form>
    </div>
    <div id="files-dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="files-dlg-save-btn" style="width:auto">Сохранить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" id="files-dlg-cancel-btn" style="width:auto">Отмена</a>
    </div>


    <div id="groups-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id="categories-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'move_to_root_category'">Переместить в корень</div>
        <div data-options="id:'add'">Добавить категорию</div>
        <div data-options="id:'add_link'">Добавить ссылку</div>
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id="links-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'find'">Найти в категориях</div>
        <div data-options="id:'add'">Добавить ссылку</div>
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id="category-links-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'add'">Добавить ссылку</div>
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id="notes-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'add'">Добавить заметку</div>
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id="files-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'add'">Добавить</div>
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
    <div id="tags-mm" class="easyui-menu" style="width:auto;">
        <div data-options="id:'add'">Добавить</div>
        <div data-options="id:'edit'">Радактировать</div>
        <div data-options="id:'delete'">Удалить</div>
    </div>
</div>

<script type="module">
import * as m from "./static/app/modules/__init__.js";

hotkeys('ctrl+a', function (event, handler){
    event.preventDefault();

    switch (handler.key) {
        case 'ctrl+a': alert('you pressed ctrl+a!'); 
        break;
    }
});

$(document).ready(() => {
    m.Groups.fnPrepare();
    m.Categories.fnPrepare();
    m.Tags.fnPrepare();
    m.Links.fnPrepare();
    m.Domains.fnPrepare();
    m.Notes.fnPrepare();
    m.CategoryLinks.fnPrepare();
    m.Files.fnPrepare();

    m.NotificationManager.fnPrepare();
})
</script>