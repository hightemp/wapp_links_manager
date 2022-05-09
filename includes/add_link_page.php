<div class="easyui-panel" title="Новая ссылка" style="width:100%;padding: 10px;">
    <form id="add-link-fm" method="post">
        <div style="margin-bottom:10px">
            <label>Группа:</label>
            <div class="input-with-btn">
                <div><input id="add-link-group_id" name="group_id" class="easyui-combobox" style="width:100%"></div>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-remove" id="add-link-group-clean-btn" style="width:auto"></a>
            </div>
        </div>
        <div style="margin-bottom:10px">
            <label>Категория:</label>
            <div class="input-with-btn">
                <div><input id="add-link-category_id" name="category_id" class="easyui-combotree" style="width:100%"></div>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-remove" id="add-link-category-clean-btn" style="width:auto"></a>
            </div>
        </div>

        <div style="margin-bottom:10px">
            <input 
                id="add-link-url" class="easyui-textbox" name="url" style="width:100%" 
                value="<?php echo @$_REQUEST["url"]; ?>"
            >
        </div>
        <div style="margin-bottom:10px">
            <input 
                id="add-link-name" class="easyui-textbox" name="name" style="width:100%" 
                value="<?php echo @$_REQUEST["name"]; ?>"
            >
        </div>
        <div style="margin-bottom:10px">
            <input 
                id="add-link-description" class="easyui-textbox" name="description" style="width:100%;height:100px" 
            >
        </div>
    </form>
    <div style="text-align:center;padding:5px 0">
        <a href="javascript:void(0)" class="easyui-linkbutton" id="add-link-save-btn">Отправить</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" id="add-link-cancel-btn">Очистить</a>
    </div>
</div>

<script>
<?php include ROOT_PATH."/static/app/modules/add_link.js" ?>
</script>