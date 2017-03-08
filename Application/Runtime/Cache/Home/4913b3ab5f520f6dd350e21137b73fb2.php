<?php if (!defined('THINK_PATH')) exit(); if($excel_count != 0 ): ?>上一次还有<?php echo ($excel_count); ?>条记录没有上传<br /><a href="<?php echo U('index/upload');?>">只上传这<?php echo ($excel_count); ?>条记录</a> | <a href="<?php echo U('index/d');?>">清空记录</a><br />
<?php else: endif; ?>
<form method="post" action="<?php echo U('index/add');?>" enctype="multipart/form-data">
    <h3>导入Excel表：</h3><input  type="file" name="file_stu" />

    <input type="submit"  value="导入" />
</form>