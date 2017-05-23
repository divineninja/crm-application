jQuery( document ).ready( function( $ ){
    $('.datepicker').datepicker();
    var modalContainer = $( '#modal_form' );
    var modalContent = modalContainer.find('.modal-body');
    
    function get_ids( ids ){
        var data = [];
        $.each( ids, function(key,value) {
            if($(this).attr('checked')){
                data.push($(this).val());
            }
        });
        return data;
    }
    
    function disable_all_inputs(){
        $('.crm-first-part').find('input').attr('disabled','disabled');
        $('.crm-first-part').find('select').attr('disabled','disabled');
        $('.crm-first-part').find('button').attr('disabled','disabled');
        $('.crm-first-part').find('.customer-phone').removeAttr('disabled');
    }
    
    function enable_all_inputs(){
        $('.crm-first-part').find('input').removeAttr('disabled');
        $('.crm-first-part').find('select').removeAttr('disabled');
        $('.crm-first-part').find('button').removeAttr('disabled');
    }
    
    // disable all inputs on ;load
    disable_all_inputs();
    
    
    $( '#show_add_item, .show_add_item' ).click( function(){
        modalContainer.modal( 'show' );
        modalContainer.find('.modal-body').html('Loading content please wait');
        var member_form_uri = $(this).data('url');
        var title = $(this).data('title');
        if(title==""){ title = "Register"; }else{ title = "Register " + title;}
        if( !title ){
            title = '';
        }   
            $.get( member_form_uri, function(response){
                modalContainer.find('.modal-title').html(title);
                modalContent.html( response );
            });
    });

    $( '.edit_item' ).click( function(){        
        
        var ids = get_ids( $('.item_id') );
        if( ids.length == 1 ){
            modalContainer.find('.modal-body').html('Loading content please wait');
            var member_form_uri = $(this).data('url')+'/'+ids['0'];
            modalContainer.modal( 'show' );
            $.get( member_form_uri, function(response){
                modalContainer.find('.modal-title').html('Edit');
                modalContent.html( response );
            });
        }else{
            alert('Please select only 1 item.')
        }
    });

    $( '.show_modal' ).click( function(){       
        var member_form_uri = $(this).attr('data-url');
        var title = $(this).attr('title')
        modalContainer.find('.modal-body').html('Loading content please wait');
        modalContainer.modal( 'show' );
        modalContainer.find('.modal-title').html(title);
        $.get( member_form_uri, function(response){
            modalContent.html(response);
        });
    });
    
    /*
     * Delete Members
     * 
     */
    $( '.delete_item' ).click( function(){
        var ids = $('.item_id');        
        var delete_url = $(this).data('url');
        var data = get_ids(ids);

        // stop process if there is no selected item
        if( data.length < 1 ) return;

        // show confirm modal box
        var confirmation = confirm('Are You Sure you want to delete this items?');

        if( !confirmation ) return;

        $.ajax({
            url: delete_url,
            data: {ids: data},
            type: 'POST',
            success: function(response){
                $.each( data, function(key,value){
                    $('#item-'+value).parent().parent().hide( function(){
                        $(this).remove();
                    })
                });
            },
            done: function(done){
                console.log(done)
            },
            error: function(error){
                console.log(error)
            }
        })
    });

    $(document).on( 'click', '#save_setting_modal', function(){
        modalContent.find('form').submit();
    });
    
    $( document ).on('submit', '.modal-body form#submit', function(e){
        e.preventDefault();
        var error = 0;
        var inputs = $( this ).find('.required');
            $.each( inputs, function(key,value){
                if( $( this).val() == '' ){
                    $( this ).addClass('error');
                    error++;
                }else{
                    $( this ).removeClass('error')
                }
            });
            
            if( error == 0 ){
                // submit to the form
                var url = $(this).attr('action');
                var data = $( this ).serialize();
                $('#save_setting_modal').html('Loading...');
                $.post( url, data, function( response ){
                    alert( response.message );
                    $('#save_setting_modal').html('Save Changes');
                },'json') ;
            }
    });
    
    $( document ).on( 'submit', '.form_submit', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $( this ).serialize();
        $('.login-message').html('').addClass('hide')
        $.post( url, data, function( response ){
            $('.login-message').html( response.message ).removeClass('hide');
             if( response.code == 200 ){
                setTimeout( function(){
                    window.location.reload();
                }, 600 )
             }
        },'json') ;
    });
    
    $( document ).on( 'submit', '.crm-submit', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $( this ).serialize();
        var current_value = $('.btn-primary').html();
        $('.btn-primary').html('Loading Please Wait!');
        $('.insert-application').attr('disabled','disabled');
        $.post( url, data, function( response ){
            $('.btn-primary').html(current_value);
             alert(response.message);
             if(response.reload){
                setTimeout( function(){
                    window.location.reload();
                }, 600 )
             }else if(response.code == 200){
                if(response.redirect == false){
                    // window.location.reload();
                }else{
                    if(response.last == 0){
                        window.location = response.redirect;
                    }else{
                        alert(response.message);
                        // self.close(); 
                    }
                    /* 
                        alert(response.message);
                        self.close(); 
                    */
                   
                    window.location = response.redirect;
                }
             }
        },'json') ;
    });
    
    
    $( document ).on( 'submit', '.crm-submit-item', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $( this ).serialize();
        $.post( url, data, function( response ){        
            console.log(response)
             if(response.reload){
                setTimeout( function(){
                    window.location.reload();
                }, 600 )
             }else if(response.code == 200){
                if(response.redirect == false){
                    // window.location.reload();
                }else{
                    window.location = response.redirect;
                }
                alert(response.message);
             }
        },'json');
    });
    
    $(document).on('click','.btn-disposition', function(e){
        e.preventDefault();
        var validate = confirm('Are you sure you want to dispose this application?');
        if(validate){
            $('.crm-submit').attr('action',$(this).attr('data-url'));
            $('.crm-submit').submit();
        }
    })

    $(document).on('change', '.first_name, .last_name', function(){
        $( '.display_name' ).val( $('.first_name').val() + ' ' +  $('.last_name').val())
    });
    
    var table = $('.dataTable');

    if( table.length ){
        $('.dataTable').dataTable({
            "aLengthMenu": [[15, 20, -1], [15, 20, "All"]]
        });
    }
    

    $(document).on('click','.btn-google', function(e){
        e.preventDefault();     
        window.location = google_url;
    });
    
    function closemodal(){
        $('.login-modal').fadeOut();
        $('.login-mask').fadeOut();
    }
    
    function showmodal(){
        $('.login-modal').fadeIn();
        $('.login-mask').fadeIn();
    }
    
    $('.show-login-form').click( function(e){
        e.preventDefault();
            showmodal();
        $.get( $(this).attr('href'), function(response){
            $('.login-modal').html(response)
        })
    });
    
    $('.login-mask').click( function(){
        closemodal();
    });
    
    $(document).on('mouseover','.crm-enlarge', function(){
        $(this).parent().prepend('<div class="large-question">'+$(this).html()+'</div>')
        var height = $('.large-question').height()
        $('.large-question').css('top',-20-height);
    })
    
    $(document).on('mouseleave','.crm-enlarge', function(){
        $('.large-question').remove();
    })
    
    $(document).on('change', '.select_answer', function(){
		
		// get parent ID and remove all the child of current question.
		var parent_id = $(this).data('qid');
		
		$('.parent_class_' + parent_id).remove();
		
        if($(this).data('role') == 'parent'){

            var url = site_url  + 'survey/get_child/';

            var id = $(this).data('qid');

            var gid = $(this).data('gid');

            var answer = $(this).val();
            
            $('.child_'+id+'_container').html('');
            
            if(answer == ""){return}

            $.ajax({
                url: url+id+'?answer='+answer+'&gid='+gid,
                type: "GET",
                success: function(response){
                    $('.child_'+id+'_container').html(response).addClass('highlight');
                    
                    $('.child_container').removeClass('highlight'); 
                }
            });

        } 
		
		if ($(this).data('logic') == 'parent') {

            var url = site_url  + 'survey/get_logic_questions/';

            var id = $(this).data('qid');

            var group = $(this).data('gid');

            var answer = $(this).val();
            
            var get_orders = get_survey_order_list();

			// Remove all currently added items under this parent category
            $('.added-'+id).hide( function() {
                $(this).remove();
            });

            if(answer == ""){return}
			
            $.get(url+id+'?answer='+answer, { "_": $.now(),"group": group}, function(response){
					
                if ($(response).length > 0) {
					/*console.log(response)*/
                    $.each(response, function(display_id) {
						
						$('.question-'+display_id).remove();
						// get rules
						var rules = this.rules;

						// get question order
						var order = this.order;

						// get the nearest number based on the number provided by the display question
						var nearest = closest(get_orders, order);
						
						// html content for agent display
						var html = this.html;

						// get number of condition found in  one question
						var rules_counter  = 0;
						var not_rules_counter  = 0;
						var rules_iterator = 0;
						var not_rules_iterator = 0;
						
						// base line count
						rule_count = Object.keys(rules).length;
						
						// extract rules and validate the questions
						$.each(rules, function(key, value) {
							// find parent rule
							
							var parentQuestion = $('.question-'+key);
							// if element was found, system can proceed displaying the 
							// question in agent view
							if (parentQuestion.length) {
								// code to validate answers and remove questions
								
								// get not_equal_validation
								var not_equal = condition_validation(this, 'not_equal')
								var equal = condition_validation(this, 'equal')
								// console.log(not_equal)				
								// validate each element
								rules_counter++;
								
								$.each(value, function(){
									
									// either one of the answer is correct
									// we'll terminate the process and proceed.
									var user_answer = $('.question-'+this.rq).find('.select_answer').val();
									
									if (user_answer.length) {
									
										if (this.operator == 'equal') {
											
											if(equal.length){
												var in_array = $.inArray(user_answer,equal);
												
												if(in_array => 0)
												{
													if (this.answer == user_answer) {
														rules_iterator++;
														return;
													}
												}
											}
											
										} else if(this.operator == 'not_equal') {
											// not_rules_counter++;
											
											// rules_iterator++;
											if(not_equal.length){
												
												var in_array = $.inArray(user_answer,not_equal);
												
												if(in_array => 0)
												{
													//console.log(in_array)
													// rules_iterator++;
													if (this.answer == user_answer) {
														rules_iterator--;
														console.log("deducted: " + rules_iterator)
													} else {
														rules_iterator++;
														console.log("added: " + rules_iterator)
														console.log(rules_iterator)
														return;
													}
												}
											}
											//rules_iterator++;
											// if expected answer is not equal to user answer
											
										} else if(this.operator == 'any') {
											// accept any answer
											rules_iterator++;
											return;
										}// end if any
									} //end if user_answer
								});
							}//end if parentQuestion
						}); // end each for rules
						
						/* console.log("ruels iterator " + rules_iterator)
						console.log("ruels counter " + rules_counter) */
						// this part of code is to check if the conditions are met
						console.log(rules_iterator + " +++ " + rule_count + " ; ; ; " + rules_counter + " " + display_id)
						if(rules_iterator >= rules_counter) {
							console.log('show message ' + display_id)
							include_text2html(html, nearest, order, id, display_id);
						}//end if rules counter and iterator
						
                    }); //end response each
                }//end if response
            },'json');
        }

    });
    
	
	function condition_validation(questions, condition)
	{
		var result = [];
		
		$.each(questions, function(key, val){
			
			if(this.operator === condition) {
				result.push( this.answer );
			}
		});
		
		return result;
	}
	
    function include_text2html(content, position, order, parent_id, child_id)
    {
        if (position >= order) {
            // use append
            $('.order-'+position).before(window.atob(content));
			
        } else {
            // use prepend
            $('.order-'+position).after(window.atob(content));
        }
		// add identification for parent ID to remove anytime
		$('.question-' + child_id).addClass('parent_class_'+parent_id);
		validate_questions()
    }
	
	function validate_questions()
	{
		
		var items = $('.survey-start');
		$.each(items, function(){
			var className = this.className;
			var questionClass = className.replace(/ /g, '.');
			
			$('.'+questionClass).slice(1).remove();
		})
		
	}
	
   function closest(array,num){
        var i=0;
        var minDiff=1000;
        var ans;
        for(i in array){
             var m=Math.abs(num-array[i]);
             if(m<minDiff){ 
                    minDiff=m; 
                    ans=array[i]; 
                }
          }
        return ans;
    }

    function get_survey_order_list()
    {
        var questions = $('.survey-start');
        var orders = [];
        
        $.each(questions, function(key, value) {
            orders[key] = $(this).data('order');
        });

        return orders;
    }
    
    $(document).on('change', '.question-choices', function(){
        var selected = $(this).val();
        $('.conditional_answer').html('');
        $('.paid_response_answer').html('');
        var options = $('.question-choices').find('option');
            $('.conditional_answer').append('<option value="0">Any Answer</option>')
        $.each(options,function(){
            if( jQuery.inArray($(this).val(),selected) >= 0 ){
                $('.conditional_answer').append('<option value="'+$(this).val()+'">'+$(this).html()+'</option>')
                $('.paid_response_answer').append('<option value="'+$(this).val()+'">'+$(this).html()+'</option>')
            }
        })
    });
    
    $(document).on('click', '.btn-show-addition', function(e){
        e.preventDefault();
        $('.child-container-form').append($('.parent-container-form').html());
    });
    
    $(document).on('click', '.remove-parent-form', function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
    });
    
    $('.btn-submit-form-configure').click( function(e){
        e.preventDefault();
        $('.crm-submit').submit();
    });
    
    $(document).on('change','.question-selected',function(){
        var id = $(this).val();
        var url = $(this).attr('data-url');
        var list = $(this).parent().parent().find('.choices-list');
        $(list).html("<option value=''>SELECT ANSWER</option>");
        $.ajax({
            url: url+'/'+id,
            type: 'GET',
            dataType:'json',
            success: function(response){
                $.each(response, function(){
                    $(list).append('<option value="'+this.choices_id+'" >'+this.label+'</option>')
                })
            },
            done: function(done){
                console.log(done)
            },
            error: function(error){
                console.log(error)
            }
        })
    });
    
    $(document).on('click','.enable-manual',function(){
        enable_all_inputs();
        $('.crm-first-part').append('<input type="hidden" name="manual" value="1" />');
    });
    
    $(document).on('click','.find-customer',function(){

        var id = $('.customer-phone').val();

        if(id == '') {alert('Phone Number is Required'); return; }

        var url = $(this).attr('data-url');

        var validate = $(this).attr('data-validate');

        var url = url+''+id;

        var loop_status = false;

        $.post(validate,{phone:$('.customer-phone').val()},function(response){

            if(response.code == 400){
                var previous_apps = "<ul class='notif-agent'>";

                $.each(response.apps, function() {
                    var status = (this.validation_status == "") ? 'Not Validated': this.validation_status;

                    previous_apps = previous_apps 
                                  + "<li><span class='agent-name-notif'>" 
                                  + this.first_name + " " + this.last_name 
                                  + "</span>"
                                  + "<span class='agent-notif-status'>"+status+"</span>"
                                  + this.date+ "</li>";
                });
                previous_apps = previous_apps + "</ul>";

                show_notification_modal(previous_apps);
            }else{
                $.each(dialer, function(key,value){
                    url = value + '?data=get_customer&number=' + id;
                    populate_data(url);
                });
                
            }

        },'json');
        
    });
    

    function show_notification_modal(previous_apps)
    {
        $('body').append("<div class='notification-modal'>"+
                            "<h2>" +
                                "Warning: Duplicate phone number." +
                            "</h2>" +
                            "<p>" +
                                "System detected same phone number recorded on CRM." +
                                " IT administrators will be notified once you continue and use manual process in this survey." +
                            "</p><p>Other agent who surveyed this phone numbers are:</p>"+ previous_apps +
                            "<span class='close-modal-notificatioin btn btn-info'>Dismiss</span>"+
                        "</div>");
    }

    $(document).on('click', '.close-modal-notificatioin', function() {
        $('.notification-modal').remove();
    });

    function callback_function(crm_response)
    {
        if(crm_response.code == "404"){
                    alert("Phone number not found. Please input the customer info manually. \n\nThanks\nCRM Administrator");
                    enable_all_inputs();
                }else{
                        var response = crm_response.leads;
						var last_name = (response.last_name == "") ? response.middle_name: response.last_name;
                        $('input[name="post_code"]').val(response.postal_code);
                        $('input[name="country"]').val(response.province);
                        $('select[name="title"]').val(response.title);
                        $('input[name="first_name"]').val(response.first_name);
                        $('input[name="last_name"]').val(last_name);
                        $('input[name="address1"]').val(response.address1);
                        $('input[name="address2"]').val(response.address2);
                        $('input[name="address3"]').val(response.address3);
                        $('input[name="town"]').val(response.city);
                        $('input[name="gender"]').val(response.gender);
                        $('input[name="urn_original"]').val(response.comments);
                        $('input[name="email"]').val(response.email);
                        $('input[name="website"]').val(response.website);
                        $('input[name="company"]').val(response.company);
                        $('input[name="position"]').val(response.position);
                        $('.post-code-text').html(response.postal_code);
                        $('.first-name-text').html(response.first_name)
                        $('.last-name-text').html(response.last_name);
                        $('.address-text').html(response.address1);
                        $('.county').html(response.province)
                        enable_all_inputs();
                    }
    }
    
    function populate_data(url){
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: "jsonp",
            jsonp: "callback",
            jsonpCallback: "callback_function",
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

            success: function(crm_response){                
                callback_function(crm_response);
            },

            done: function(done){
                console.log('----------------DONE----------------');
                console.log(done);
                console.log('--------------END DONE--------------');
            },
            error: function(error){
                console.log('---------------ERROR!---------------');
                console.log(error);
                console.log('-------------END ERROR!-------------');

                alert('No data found! Please check dialers url to fix the problem.');
            }
        })
    }
    
    
    
    $(document).on('click','.show-question-list', function(e){  
        e.preventDefault();
        $('.question-form').slideToggle();
    });
    
    $(document).on('click','.btn-add-new-child-condition', function(e){ 
        var parent_child = $('.parent-control-child-condition').html()
        $('.child-condition-list').append(parent_child);
    });
    
    $(document).on('change','.post-code-select', function(){
        if(!$(this).val()) return;
        $.ajax({
            url: $(this).val(),
            type: 'GET',
            success: function(response){
                $('.question-list').html(response);
            },
            done: function(done){
            },
            error: function(error){
                console.log(error)
            }
        })
        
    });
    
    $(document).on('click', '.delete-item', function(e){
        e.preventDefault();
        var confirmation = confirm("are your sure you want to delete?");        
        if(!confirmation) return;       
        var _this = $(this);
        var url = $(this).attr('href');
        $.post( url, function(response){
            $(_this).parent().parent().fadeOut(function(){
                $(this).remove();
            });
        },'json');
    });
    
    $(document).on('click', '.delete-condition-question', function(e){
        
        e.preventDefault();
        var confirmation = confirm("are your sure you want to delete?");        
        if(!confirmation) return;
        var _this = $(this);
        var url = $(this).attr('href');
        
        $.post( url, function(response){
            if(response.code == 200){
                $(_this).parent().parent().fadeOut(function(){
                    $(this).remove();
                });
            }
        },'json');
    });
    
    $('.post-code').blur(function(){    $('.post-code-text').html($(this).val());       })
    $('.first-name').blur(function(){   $('.first-name-text').html($(this).val());      })
    $('.last-name').blur(function(){    $('.last-name-text').html($(this).val());       })
    $('.address').blur(function(){      $('.address-text').html($(this).val());         })
    $('.title').blur(function(){        $('.title-text').html($(this).val());           })
    
    $(document).on('click','.save_order', function(e){
        e.preventDefault();
        $('#question_order').submit();
    })
    
    $(document).on('submit','#question_order', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();
        var orig_data = $('.save_order').html();
        $('.save_order').html('<i class="fa fa-spinner fa-spin"></i> Loading.. Please wait.');
        $.post(url,data, function(response){ 
             $('.save_order').html(orig_data);
        });
    });
    
    $(document).on('submit','.form-session', function(e){
        e.preventDefault();
        var data = $(this).serialize();
        console.log(data);
        window.retrieve_url = window.orig_url + '&' +data;
        retrieve_data(retrieve_url);
    });
    

    $(document).on('click','.show-slick-menu', function(){
        
        $('.menu-content').toggleClass('show');
        
        $('.page-main-slick').toggleClass('menu-enabled');

    });

    /* 
    $(document).on('click','.show-survey',function(e){
        e.preventDefault();     
        window.open($(this).attr('href'),'Agent CRM', 'left=0,top=0,width='+$(document).width()+',height='+$(document).height()+',toolbar=1,resizable=1');
    }) 
    */
    

    $(document).on('click','#show-individual-result', function() {
        
        var validate = $('#from_date').val();

        if(validate.length){
            $('#hourlyReport-idividual').submit();
        }
    });

    $(document).on('submit','#hourlyReport-idividual', function(e) {
        e.preventDefault();
        var url = $(this).attr('action') + '/' + $('#from_date').val();
        $('.hourly-agent-display').css({
            'opacity': '0.5'
        }).html('<p>Loading Content... Please wait...</p>')
        $.get(url, function(response){
            $('.hourly-agent-display').html(response).css({
                'opacity': 1
            });
            $('#agent-data-table').dataTable();
        });
    });

    $(document).on('dblclick','.page-title', function(){
        $('.change-campaign').toggle();
        $('.campaing-changer').toggle();

        $.get($('#campaign_url').val(), function(response){
            $('.campaing-changer').html(response);
        })
    });
    $(document).on('change','.campaign-changed', function(){
        $('#changer-form').submit();
    });

    $('.type-icon').hover( function(){
        $('.icon-title-wrapper').html($(this).find('a span').attr('title'));
    },function(){
        $('.icon-title-wrapper').html('');
    });

    $(document).on('change','.fader', function(){
        console.log($(this).parent().find('.range-output').html($(this).val()));
    });

    $(document).on('change', '.scoring', function(e){
        
        var key = $(this).data('key');
        var val = $(this).val();
        if(val == 1) {
            $('#rangeInput_'+key).attr('min', 0);
            $('#rangeInput_'+key).attr('max', 0);
            $('#rangeInput_'+key).attr('value', 0);
        }else if(val == 2) {
            $('#rangeInput_'+key).attr('min', 1);
            $('#rangeInput_'+key).attr('value', 1);
            $('#rangeInput_'+key).attr('max', 25);
        }else if(val == 3) {
            $('#rangeInput_'+key).attr('value', 26);
            $('#rangeInput_'+key).attr('min', 26);
            $('#rangeInput_'+key).attr('max', 50);
        }else if(val == 4) {
            $('#rangeInput_'+key).attr('value', 51);
            $('#rangeInput_'+key).attr('min', 51);
            $('#rangeInput_'+key).attr('max', 75);
        }else if(val == 5) {
            $('#rangeInput_'+key).attr('value', 76);
            $('#rangeInput_'+key).attr('min', 76);
            $('#rangeInput_'+key).attr('max', 100);
        }
    });
    $(document).on('click', '.tab-control a', function(e){
        e.preventDefault();
        $('.tab-control').removeClass('active');
        $(this).parent().addClass('active');

        var id = $(this).attr('href');

        $('.tab-content').removeClass('active');
        $(id).addClass('active');
    });

    $(document).on('click', '.show_confirm', function(e){
        var con = confirm('Are you sure you want to delete?');

        if (!con) {
            return false;
        }
    })
    
    $(document).on('click', '.modal-change', function(e){
        e.preventDefault();
        
        var member_form_uri = $(this).attr('data-url');
        var title = $(this).attr('title')
        modalContainer.find('.modal-body').html('Loading content please wait');
        modalContainer.find('.modal-title').html(title);

        $.get(member_form_uri, function(response){
            modalContent.html(response);
        });
    });

    $(document).on('click', '.check_supression',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var _this = $(this);
        
        $(_this).html('<i class="fa fa-spin fa-spinner"></i> Finding Phone number in suppression lists. Please Wait...');
        
        $.get(url, function(response){
            if( response.result == 0 ) {
                // negtive response from the query means this is positive for agent.
                // agent can ask this question.
                $(_this).html('&check; Passed: You can ask this question.');
                $(_this).parent().parent().addClass('enable_this_question');
            } else {
                // question will be disabled for this session.
                $(_this).parent().parent().addClass('disable_question');
                $(_this).html('&times; Failed: Please do not ask this question.');
            }
        },'json');
    });

    $(document).on('click', '#suppress_all_questions',function(e){
        e.preventDefault();
        $('.check_supression').trigger('click');
        $(this).attr("disabled","disabled");
    });
	
	
	window.resetValue = function(ids)
	{
		var id_array = ids.split(',');
		
		$.each(id_array, function(key,value) {
			jQuery('Select[data-qid="'+value+'"]').val("");
		});
		
		
	}
	
	// shorthand change status of agent login
	$(document).on('click', '.color-green', function(element){
		
		element.preventDefault();
		
		var id = $(this).parent().parent().find('.item_id').val();
		var _this = $(this);
		var url = site_url + 'agent/update_object';
		
		var data = {
			'agent_id' : id,
			'status' : 0
		}
		
		$.post(url, data, function(response){
			console.log(response);
			$(_this).removeClass('fa  fa-toggle-on color-green').addClass('fa  fa-toggle-off color-grey');
		});
	});
	
});
