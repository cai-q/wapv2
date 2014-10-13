function updateCommentCount(aid,tids){
		
	if(tids && aid){			
		var url = 'http://comment.caixin.com/interface/cc.php?app_id=' + aid + '&ids='+encodeURIComponent(tids)+'&callback=?';
		jQuery.getJSON(url, 
            		function(data){					
				for(var i in data){
					var exps = 'em[aid=' + aid + '][tid=' + data[i].tid + ']';
					jQuery(exps).each(function(){
						if(data[i].count > 0)							
							jQuery(this).html(data[i].count + "");
					});
				}
			}
		);
	}
}

function countComment(){

        var apps = new Object();
        jQuery('em[aid][tid]').each(function(){
                var aid = jQuery(this).attr("aid");
                var tid = jQuery(this).attr("tid");
                var tids = "";
		if(aid == "1")aid = "100";
                if(aid in apps){
                        var tids = apps[aid];
                        tids = tids + ";" + tid;
                }else{
                        tids = tid;
                }
                apps[aid] = tids;
        });

        for(k in apps){
                updateCommentCount(k,apps[k]);
        }

}

if(typeof(jQuery) != "undefined")
	jQuery(function(){countComment();});
