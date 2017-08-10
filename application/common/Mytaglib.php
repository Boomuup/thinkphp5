<?php
namespace app\common\taglib;
use think\template\TagLib;
class Mytaglib extends TagLib{
    /**
     * 定义标签列表
     */
    protected $tags   =  [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'close'     => ['attr' => 'time,format', 'close' => 0], //闭合标签，默认为不闭合
        'open'      => ['attr' => 'name,type', 'close' => 1],
        'ueditor'=> array('attr'=>'name,content,toolbars,min-height','close'=>0),
    ];

    /**
     * 引入ueidter编辑器
     * @param string $tag  name:表单name content：编辑器初始化后 默认内容
     */
    public function tagUeditor($tag){
        $name=isset($tag['name']) ? $tag['name'] : 'content';
        $content=isset($tag['content']) ? $tag['content'] : '';

        if(isset($tag['min-height'])){
            $height = $tag['min-height'];
        }

        if($tag['toolbars']=='all'){
            $toolbars =<<<AAA
        {toolbars: [[
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
            'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
            'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
            'print', 'preview', 'searchreplace', 'drafts', 'help'
        ]],
        autoHeightEnabled: true,
        autoWidthEnabled: true,
        }
AAA;
        }else{
            $toolbars ="{toolbars: [['fullscreen','bold', 'italic','insertorderedlist','emotion',]],autoHeightEnabled: true,autoWidthEnabled: true,}";
        }
        $link=<<<php
<script id="ueditor_container" name="$name" style="width:100%;min-height:$height" type="text/plain">$content</script>
<script src="/public/static/public/ueditor1.4/ueditor.config.js"></script>
<script src="/public/static/public/ueditor1.4/ueditor.all.js"></script>
<script src="/public/static/public/ueditor1.4/lang/zh-cn/zh-cn.js"></script>
<script>
    var ue = UE.getEditor('ueditor_container',$toolbars);
</script>
php;
        return $link;
    }

    /**
     * 这是一个闭合标签的简单演示
     */
    public function tagClose($tag)
    {
        $format = empty($tag['format']) ? 'Y-m-d H:i:s' : $tag['format'];
        $time   = empty($tag['time']) ? time() : $tag['time'];
        $parse  = '<?php ';
        $parse .= 'echo date("' . $format . '",' . $time . ');';
        $parse .= ' ?>';
        return $parse;
    }

    /**
     * 这是一个非闭合标签的简单演示
     */
    public function tagOpen($tag, $content)
    {
        $type = empty($tag['type']) ? 0 : 1; // 这个type目的是为了区分类型，一般来源是数据库
        $name = $tag['name']; // name是必填项，这里不做判断了
        $parse = '<?php ';
        $parse .= '$test_arr=[[1,3,5,7,9],[2,4,6,8,10]];'; // 这里是模拟数据
        $parse .= '$__LIST__ = $test_arr[' . $type . '];';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $name . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

}

?>