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
                submit_form: function(action, string) {
                		$.post(action, string, 
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
                        ISS.change_pageContent(href);

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