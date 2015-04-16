// important so the variable scope is not window
(function() {

        // start of the ISS mini-framework
        var ISS = {
                login : function(action, email, password) {
                        //password = MD5(password);
                        $.post(action, {
                                email : email,
                                password : password
                        }, function(data) {
                                $('.loginContent').hide(0).remove();
                                $('.ISS_Content').append(data);
                                $('.mainContent').hide();
                                $('.mainContent').fadeIn(500);
                        });
                },
                logout : function() {

                        $.post('utils/logout.php', function(data) {
                                $('.mainContent').hide(0).remove();
                                $('.ISS_Content').append(data);
                                $('.loginContent').hide();
                                $('.loginContent').fadeIn(500);
                        });

                },
                /*register : function(action, email, password) {
                        //password = MD5(password);
                        $.post(action, {
                                email : email,
                                password : password
                        }, function(data) {
                                alert(data);
                        });
                },*/
                change_pageContent : function (file) {
                        $.post(file, function(data) {
                        			$('.pageContent').hide(0);
                                 $('.pageContent').html(data);
                                 $('.pageContent').fadeIn(500);
                        });

                },
                change_inlineContent : function (file) {
                        $.post(file, function(data) {
                        			$('.inlineContent').hide(0);
                                 $('.inlineContent').html(data);
                                 $('.inlineContent').fadeIn(500);
                        });

                },
                insert_pageContent : function (file) {
                        $.post(file, function(data) {
                        		 $('.inlineContent').hide(0);
                                 $('.inlineContent').html('<html><head></head><body>' + data + '</body></html>');
                                 $('.inlineContent').fadeIn(500);
                        });

                },                
                submit_form: function(file, string) {
                		$.post(file, string, 
                			function(data) {
                				$('.pageContent').hide(0);
                				$('.pageContent').html(data);
                				$('.pageContent').fadeIn(500);
                			});
                	
                },
        }

        $(document).ready(function() {
                $document = $(document);
                
      

                $document.ajaxStart( function() {
                         console.log('loading...');
                }).ajaxStop( function() {
                         console.log('finished!');
                });

                $document.on('click', '.loginButton', function() {
                        var action = $('#login').attr('action');
                        var email = $('#email').val();
                        var password = $('#password').val();

                        ISS.login(action, email, password);

                        return false;
                });

                $document.on('click', '.logout', function() {
                        ISS.logout();
                        return false;
                });

                $document.on('click', 'a[data-change="main"]', function() {
                        var href = $(this).attr('href');
                        
                       
                        $(".pageSidebar").children().children().css({"background": "lightblue"});
                        $(".pageSidebar").children().children().children().css({"background": "lightblue"});
                        
                        $(this).css({"background-color":"blue"});
                        
                        if($(this).parent().attr('class') == 'inlineContent')
                        {
                        	ISS.change_inlineContent(href);
                        }
                        else
                        {
                        	ISS.change_pageContent(href);
                        }
                        

                        return false;
                });
                //Menuebar hidden -> visible
                $document.on('mouseenter', 'a[data-change="main"]', function() {
                	
                	$(this).next().css({"display":"block"});
                	
                });
                //Menuebar visible -> hidden
                $document.on('mouseleave', 'div[class="pageSidebarEntry"]', function() {
                	
                	
                	$(this).find('div[class="pageSidebarMenu"]').css({"display":"none"});
                	
                });
                
                
                $document.on('click', 'a[data-change="inline"]', function() {
                        var href = $(this).attr('href');
                        $(this).replaceWith('<iframe class="inlineContent" border="none"></iframe>');
                        ISS.insert_pageContent(href);

                        return false;
                });
               
               $document.on('submit', 'form', function() {
               			
               			var action = $(this).attr('action');
               			var string = $(this).serialize();
               			
               			ISS.submit_form(action, string);
               	
               			return false;
               	
               }); 
                
        });
        

})();