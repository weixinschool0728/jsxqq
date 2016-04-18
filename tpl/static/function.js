/**
 * 后台公共JS函数库
 *
 */

function confirmurl(url,message) {
	window.top.art.dialog.confirm(message, function(){
    	redirect(url);
	}, function(){
	    return true;
	});
	//if(confirm(message)) redirect(url);
}

function redirect(url) {
	location.href = url;
}

/**
 * 全选checkbox,注意：标识checkbox id固定为为check_box
 * @param string name 列表check名称,如 uid[]
 */
function selectall(name) {
	if ($("#check_box").attr("checked")=='checked') {
		$("input[name='"+name+"']").each(function() {
  			$(this).attr("checked","checked");

		});
	} else {
		$("input[name='"+name+"']").each(function() {
  			$(this).removeAttr("checked");
		});
	}
}
function openwinx(url,name,w,h) {
	if(!w) w=screen.width-4;
	if(!h) h=screen.height-95;
    window.open(url,name,"top=100,left=400,width=" + w + ",height=" + h + ",toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no");
}

//表单提交时弹出确认消息
function submit_confirm(id,msg,w,h){
	if(!w) w=250;
	if(!h) h=100;
	  window.top.art.dialog({
      content:msg,
      lock:true,
      width:w,
      height:h,
      ok:function(){
        $("#"+id).submit();
        return true;
      },
      cancel: true
    });
}
function dele(id){
	if(confirm('确定删除该项吗？')){
		location="index.php?g=System&m=Funintro&a=home_delete&id="+id;
	}
}
function delete_cache(){
	$.ajax({
		type:"post",
		url:"?g=Home&m=Index&a=cache_ajax",
		datatype:"json",
		data:{
			id:'1'
		},
		success:function(sta){
			if(sta){
				alert('清除缓存成功！');
				//location.reload();//刷新本页
			}else{
				alert('清理缓存失败！');
			}
		}
	})
}
function addLink(domid,iskeyword){
	$.ajax({
		type:'post',
		url:'index.php?g=System&m=Funintro&a=link_ajax',
		datatype:'json',
		data:{
			id:'1'
		},
		success:function(sta){
			if(sta.length<'4'){
				var obj=JSON.parse(sta);
				if(obj>'0'){
					art.dialog.data('domid', domid);
					art.dialog.open('?g=User&m=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入功能链接',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
				}else{
					alert('请先进入管理中心里面的功能管理，再添加功能链接！！！');
				}
			}else{
				alert('管理中心或总后台登录超时，请重新登录！！！');
			}

		}
	})
}
$(function(){
	$('.batch_delete').click(function(){
		var mes='确定批量删除吗？';
		window.top.art.dialog.confirm(mes, function(){
				document.forms['myform_zw'].submit();
			}, function(){
			    return true;
			});
	})
})
function o_delete(id,mes){
	window.top.art.dialog.confirm(mes, function(){
		window.location="index.php?g=System&m=Funintro&a=dels&id="+id;
		}, function(){
		    return true;
		});
}
function jr_delete(id,mes){
	window.top.art.dialog.confirm(mes, function(){
		window.location="index.php?g=System&m=Funintro&a=holi_dels&id="+id;
		}, function(){
		    return true;
		});
}
$(function(){
	$(".kind_del").click(function(){
		var mes='确定批量删除吗，删除后，对应分类下面的功能也会批量删除！！';
		window.top.art.dialog.confirm(mes, function(){
			document.forms['myform_zw'].submit();
			}, function(){
			    return true;
			});

		})
})
$(function(){
	$(".bath_jr_del").click(function(){
		var mes='确定批量删除吗，删除后，对应分类下面的功能也会批量删除！！';
		window.top.art.dialog.confirm(mes, function(){
			document.forms['myform_s'].submit();
			}, function(){
			    return true;
			});
	})
})
$(function(){
	$('#checkAll').click(function(){
		if($(this).attr('checked')){
			$(':checkbox').attr('checked','true');
		}else{
			$(':checkbox').removeAttr('checked');
		}
	});
});
$(function(){
	$('.two_kind').click(function(){
		window.location="index.php?g=System&m=Funintro&a=holi_indexs";
	})
})
function two_j(){
	window.location="index.php?g=System&m=Funintro&a=holi_indexs&pid=88&level=3";
}
$(function(){//使用ajax是为了保证提交后的数据一定是对的，不会返回后，DIV隐藏，看不到值不美观
	$("#click_sub_add,#click_sub_edit").click(function(){
		var id=$(".hidden_id").val();
		var first_kind=$("#first_kind option:selected").val();
		var first_holi=$("#first_holi option:selected").val();
		var two_kind=$("#two_kind option:selected").val();
		var menu_link=$("#menu_link").attr("value");
		if($(":checkbox").is(":checked")){
			var public_id=1;
		}
		$.ajax({
			type:"post",
			url:"index.php?g=System&m=Funintro&a=check_ajax",
			datatype:"json",
			data:{
				first_kind:first_kind,
				first_holi:first_holi,
				two_kind:two_kind,
				menu_link:menu_link,
				public_id:public_id
			},
			success:function(sta){
				var obj = JSON.parse(sta);
				if(obj==200){
					document.forms['form'].submit();
				}else{
					alert(obj);
				}
			}
		})
		}
	)
})

function chooseFile(domid,type){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=User&m=Attachment&a=index&type='+type,{lock:true,title:'选择文件',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}
function upyunPicUpload(domid,width,height,token){
	art.dialog.data('width', width);
	art.dialog.data('height', height);
	art.dialog.data('domid', domid);
	art.dialog.data('lastpic', $('#'+domid).val());
	art.dialog.open('?g=User&m=Upyun&a=upload&token='+token+'&width='+width,{lock:true,title:'上传图片',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}
function viewImg(domid){
	if($('#'+domid).val()){
		var html='<img src="'+$('#'+domid).val()+'" />';
	}else{
		var html='没有图片';
	}
	art.dialog({title:'图片预览',content:html,lock:true,background: '#000',opacity: 0.45});
}
$(function(){
	$("#add_img_one").click(function(){
		$(".two_img").css("display","")
	})
	$("#add_img_two").click(function(){
		$(".three_img").css("display","")
	})
	$("#add_img_three").click(function(){
		$(".four_img").css("display","")
	})
	$("#add_img_four").click(function(){
		$(".five_img").css("display","")
	})
})
function hide_img_two(){
	$(".two_img").css("display","none")
	$("#img2").attr("value","")
}
function hide_img_three(){
	$(".three_img").css("display","none")
	$("#img3").attr("value","")
}
function hide_img_four(){
	$(".four_img").css("display","none")
	$("#img4").attr("value","")
}
function hide_img_five(){
	$(".five_img").css("display","none")
	$("#img5").attr("value","")
}
// 功能模块搜索功能
function butt(){
	var value=$("#myform").attr("value");
	if(value.length>0){
		$('form').submit();
	}else{
		alert('请输入搜索内容！');
	}
}
