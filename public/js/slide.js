$(document).ready(function(){
	var i = 1;

 		$(".dzax").click(function(){
    		if(i < 1){
					i = 4
				}
			$(".slide_pic").attr('src','pic/slide/slide_'+ i-- +'.jpg');
    	});

		$(".aj").click(function(){
				if(i > 4){
						i = 1
					}
	        	$(".slide_pic").attr('src','pic/slide/slide_'+ i++ +'.jpg');
   		}); 
}