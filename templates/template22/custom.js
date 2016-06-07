



$(document).ready(function(){



    $(window).scroll(function(){

    	
    	
    	
    	if ($(window).scrollTop() > 500) 
    	{
    		$('#scroll_box').slideDown("slow");
   	 	}
    	else 
    	{
    		$('#scroll_box').slideUp("slow");
    	};
    });
    
    
        $("#scroll_box").click
   		(
			function () 
			{
				$(window).scrollTo(0, 1000, {axis:'y'} );
			}
		);
 

        
         $(".blogin").hover(function() {
              $(this).stop().animate({ backgroundColor: "#2fdbf0"}, 600);
          },function() {
              $(this).stop().animate({ backgroundColor: "#1aa6b7" }, 600);
          });   
          
          
          $(".bsignup").hover(function() {
              $(this).stop().animate({ backgroundColor: "#ffa303"}, 600);
          },function() {
              $(this).stop().animate({ backgroundColor: "#ed7311" }, 600);
          });   
          
          $(".home_btn1").hover(function() {
              $(this).stop().animate({ backgroundColor: "#2fc9dc"}, 600);
          },function() {
              $(this).stop().animate({ backgroundColor: "#1aa6b7" }, 600);
          });  
          
           $(".home_btn2").hover(function() {
              $(this).stop().animate({ backgroundColor: "#f98426"}, 600);
          },function() {
              $(this).stop().animate({ backgroundColor: "#ed7311" }, 600);
          });  


   
        
        

        
        $(".lanbox").colorbox({width:"730",height:"420", inline:true, href:"#languages_lite2"});
        
        $(".lbox").colorbox({width:"",height:"", inline:true, href:"#login_box"});
        
        $('#search').keyup(function() 
		{
 		 	show_search();
		});
	
	
		$("#instant_search").hover
		(
			function () 
			{
				
			},
			function () 
			{
				$('#instant_search').slideUp("fast");
				document.getElementById('instant_search').innerHTML ="";
			}
		);

    });
    
    
    
    
    
