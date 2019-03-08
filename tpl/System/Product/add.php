<include file="Public:header"/>
        <div class="mainbox">
            <div id="nav" class="mainnav_title">
                <ul>
                    <a href="{:U('index')}">产品列表</a> | 
                    <a href="{:U('add')}"  class="on">添加产品</a>
                </ul>
            </div>
            <form method="post" action="{:U('add_modify')}" refresh="true" enctype="multipart/form-data" >
                <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                    <tr>
                        <th width="160">产品标题　</th>
                        <td><input type="text" class="input-text" name="title" id="title" value="{$ac.title}" validate="required:true" /></td>
                    </tr>
                    <tr>
                        <th width="160">产品描述　</th>
                        <td><textarea rows="10" cols="60" name="desc" id="desc">{$ac.desc}</textarea></td>
                    </tr>
                    
                    <tr>
                        <th width="160">正面图</th>
                        <td>
                        <span class="config_upload_image_btn"><input type="button" value="上传图片" class="button" style="margin-left:0px;margin-right:10px;"></span>
                        <input type="text" class="input-text input-image" name="front_pic" id="config_site_water_mark" value="{$ac.front_pic}" size="48"  tips="请填写封面图片的网址，包含（http://域名）！">
                         </td>
                    </tr>
                    <if condition="$ac['front_pic']">
                    <tr>
                            <th width="160"></th>
                            <td><img src="{$config.site_url}{$ac.front_pic}" width="280" height="180" id="front_pic"></td>
                        </tr>
                    </if> 
                    <tr>
                        <th width="160">反面图</th>
                        <td>
                        <span class="config_upload_image_btn"><input type="button" value="上传图片" class="button" style="margin-left:0px;margin-right:10px;"></span>
                        <input type="text" class="input-text input-image" name="reserve_pic" id="config_site_water_mark" value="{$ac.reserve_pic}" size="48"  tips="请填写封面图片的网址，包含（http://域名）！">
                         </td>
                    </tr>
                    <if condition="$ac['reserve_pic']">
                    <tr>
                            <th width="160"></th>
                            <td><img src="{$config.site_url}{$ac.reserve_pic}" width="280" height="180" id="reserve_pic"></td>
                        </tr>
                    </if>
                    <tr>
                        <th width="160">大图上</th>
                        <td>
                        <span class="config_upload_image_btn"><input type="button" value="上传图片" class="button" style="margin-left:0px;margin-right:10px;"></span>
                        <input type="text" class="input-text input-image" name="big1" id="config_site_water_mark" value="{$ac.big1}" size="48"  tips="请填写封面图片的网址，包含（http://域名）！">
                         </td>
                    </tr>
                    <if condition="$ac['big1']">
                    <tr>
                            <th width="160"></th>
                            <td><img src="{$config.site_url}{$ac.big1}" width="280" height="180" id="big1"></td>
                        </tr>
                    </if>
                    <tr>
                        <th width="160">大图左</th>
                        <td>
                        <span class="config_upload_image_btn"><input type="button" value="上传图片" class="button" style="margin-left:0px;margin-right:10px;"></span>
                        <input type="text" class="input-text input-image" name="big2" id="config_site_water_mark" value="{$ac.big2}" size="48"  tips="请填写封面图片的网址，包含（http://域名）！">
                         </td>
                    </tr>
                    <if condition="$ac['big2']">
                    <tr>
                            <th width="160"></th>
                            <td><img src="{$config.site_url}{$ac.big2}" width="280" height="180" id="big2"></td>
                        </tr>
                    </if>
                    <tr>
                        <th width="160">大图右</th>
                        <td>
                        <span class="config_upload_image_btn"><input type="button" value="上传图片" class="button" style="margin-left:0px;margin-right:10px;"></span>
                        <input type="text" class="input-text input-image" name="big3" id="config_site_water_mark" value="{$ac.big3}" size="48"  tips="请填写封面图片的网址，包含（http://域名）！">
                         </td>
                    </tr>
                    <if condition="$ac['big3']">
                    <tr>
                            <th width="160"></th>
                            <td><img src="{$config.site_url}{$ac.big3}" width="280" height="180" id="big3"></td>
                        </tr>
                    </if>
                    <tr>
                        <th width="160">右边标题　</th>
                        <td><textarea name="r_title" id="" cols="30" rows="10" value="{$ac.r_title}" >{$ac.r_title}</textarea><!-- <input type="text" class="input-text" name="r_title" id="title" value="{$ac.r_title}" validate="required:true" /> --></td>
                    </tr>
                    <tr>
                        <th width="160">右边内容　</th>
                        <td><textarea name="r_info" id="" cols="30" rows="10" value="{$ac.r_info}" >{$ac.r_info}</textarea><!-- <input type="text" class="input-text" name="r_info" id="title" value="{$ac.r_info}" validate="required:true" /> --></td>
                    </tr>
                    
                    
                </table>
                <div class="btn">
                    <input type="hidden" name="id" value="{$Think.get.id}">
                    <input type="submit"   value="提交" class="button" />
                    <input type="reset"  value="取消" class="button" />
                </div>
            </form>
        </div>
        <script src="{$static_public}kindeditor/kindeditor.js"></script>
    <script type="text/javascript">
        KindEditor.ready(function(K){
            kind_editor = K.create("#desc",{
                width:'402px',
                height:'320px',
                resizeType : 1,
                allowPreviewEmoticons:false,
                allowImageUpload : true,
                filterMode: true,
                items : [
                    'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link'
                ],
                emoticonsPath : './static/emoticons/',
                uploadJson : "{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news"
            });
        });
        
    </script>
        <script type="text/javascript">
            KindEditor.ready(function(K){
                var site_url = "{$config.site_url}";
                var editor = K.editor({
                    allowFileManager : true
                });
                $('.config_upload_image_btn').click(function(){
                    var upload_file_btn = $(this);
                    editor.uploadJson = "{:U('ajax_upload_pic')}";
                    editor.loadPlugin('image', function(){
                        editor.plugin.imageDialog({
                            showRemote : false,
                            clickFn : function(url, title, width, height, border, align) {
                                upload_file_btn.siblings('.input-image').val(url);
                                $("#titleimage").attr("src", site_url+url);
                                editor.hideDialog();
                            }
                        });
                    });
                });
            });
        </script>
        <style>
            .table_form{border:1px solid #ddd;}
            .tab_ul{margin-top:20px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
            .tab_ul>li{position:relative;display:block;float:left;margin-bottom:-1px;}
            .tab_ul>li>a {position: relative;display: block;padding: 10px 15px;margin-right: 2px;line-height: 1.42857143;border: 1px solid transparent;border-radius: 4px 4px 0 0;padding: 7px 12px 8px;min-width: 100px;text-align: center;}
.tab_ul>li>a, .tab_ul>li>a:focus {border-radius: 0!important;border-color: #c5d0dc;background-color: #F9F9F9;color: #999;margin-right: -1px;line-height: 18px;position: relative;}
.tab_ul>li>a:focus, .tab_ul>li>a:hover {text-decoration: none;background-color: #eee;}
.tab_ul>li>a:hover {border-color: #eee #eee #ddd;}
.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {color: #555;background-color: #fff;border: 1px solid #ddd;border-bottom-color: transparent;cursor: default;}
.tab_ul>li>a:hover {background-color: #FFF;color: #4c8fbd;border-color: #c5d0dc;}
.tab_ul>li:first-child>a {margin-left: 0;}
.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {color: #576373;border-color: #c5d0dc #c5d0dc transparent;border-top: 2px solid #4c8fbd;background-color: #FFF;z-index: 1;line-height: 18px;margin-top: -1px;box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);}
.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {color: #555;background-color: #fff;border: 1px solid #ddd;border-bottom-color: transparent;cursor: default;}
.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {color: #576373;border-color: #c5d0dc #c5d0dc transparent;border-top: 2px solid #4c8fbd;background-color: #FFF;z-index: 1;line-height: 18px;margin-top: -1px;box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);}
.tab_ul:before,.tab_ul:after{content: " ";display: table;}
.tab_ul:after{clear: both;}
        </style>
<include file="Public:footer"/>