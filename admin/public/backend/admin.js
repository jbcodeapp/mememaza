const url = $('meta[name=get-url]').attr("content");
const isAjaxRequest = false;
const _ = $('body');

var CommonManager = {
	ajax : function(url, callback, type, data, dataType, extraData, beforeLoad) {
		dataType = dataType || 'json';
		type = type || 'GET';
		data = data || {};
		extraData = extraData || {};

		var request = $.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: url,
			cache: false,
			type: type,
			data: data,
			dataType: dataType,
		});

		request.done(function(data) {
			callback(data, extraData);
		});

		request.fail(function(jqXHR, exception) {
			CommonManager.ajaxFailResponse(jqXHR, exception);
		});
	},
	
	ajaxFailResponse: function(jqXHR, exception) {
		if(jqXHR.status == 422) {
			$('.error_msg').html('');
			var errors = jqXHR.responseJSON.errors;
			console.log(errors);
			$.each(errors, function (key, value) {
				$('.error_'+key+'_msg').html(value);
			});
			return false;
		} else {
			if(jqXHR.status === 0) {
				alert("Not connect.\n Verify Networkssssssssss.");
			} else if (jqXHR.status == 404) {
				alert("[404] Requested page not found.");
			} else if (jqXHR.status == 500) {
				alert("[500] Internal Server Error.");
			} else if (exception === 'parsererror') {
				alert("Requested JSON parse failed.");
			} else if (exception === 'timeout') {
				alert("Time out error.");
			} else if (exception === 'abort') {
				alert("Ajax request aborted.");
			} else {
				alert("Uncaught Error.\n");
			}
		}
	},
	
	ajaxupload : function(url, callback, type, data, dataType, extraData, beforeLoad) {
		dataType = dataType || 'json';
		type = type || 'GET';
		data = data || {};
		extraData = extraData || {};
		var request = $.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: url,
			cache: false,
			type: type,
			data: data,
			processData: false,
			contentType: false,
			dataType: dataType,
		});

		request.done(function(data) {
			callback(data, extraData);
		});

		request.fail(function(jqXHR, exception) {
			CommonManager.ajaxFailResponse(jqXHR, exception);
		});
	},
	
	validateFields: function(classname, message, target) {
		$('.err').remove();
		$.each(message, function(key, value ) {
			$(classname+key).after('<small class="err help-block form-text text-danger '+target+'">'+value+'</small>');
		});
	},
}

var CategoryManager = {
	handleFormResponse:function(response, extraData) {
		var current = extraData.current;
		current.find(":submit").html(current.find(":submit").attr('data-text')).prop('disabled', false);
		if(response.status == 'errors') {
			CommonManager.validateFields('.',response.errors, 'a');
			return false;
		} else if(response.status == 'error') {
			alert(response.msg);
			return false;
		}
		location.href = url+'/categories';
	},
	
	get_cat_hir:function(response, extraData) {
		var current = extraData.child_cat;
		
		$.each(response, function(index, item) {
			current.append(new Option(item.name, item.id));
		});
		
	},
	
	init: function() {
		_.on('submit', '#category_attribute_form', function(e) {
			
			//var count = $('.group', $('#category_attribute_form')).length;
			//alert(count);
			//return false;
		});
		
		_.on('change', '.parent_id', function(e) {
			let value = $(this).val();
			var child_cat = $(".child_cat_id");
			child_cat.empty();
			child_cat.append(new Option('Select Child Category', ''));
			if(value > 0)
			{
				let path = url+'/get_category_level/'+value+'/2';
				CommonManager.ajaxupload(path, CategoryManager.get_cat_hir, 'get', {'category_id':value, 'level':2}, 'json', {'child_cat': child_cat});
			}
		});
		
		/* _.on('change', '.is_parent_id', function(e) {
			let value = $(this).val();
			//var parent_cat = $(".parent_id");
			var child_cat = $(".child_cat_id");
			
			child_cat.empty();
			child_cat.append(new Option('Select Child Category', ''));
			
			if(value == 3) {
				//var url = $('#category_form').attr('action');
				//CommonManager.ajaxupload(url, CategoryManager.handleFormResponse, 'get', {}, 'json', {'current': current});
			}
		}); */
		
		_.on('submit', '#category_form', function(e) {
			var url = $('#category_form').attr('action');
			var current = $(this);
			current.find(":submit").html('Loading...').prop('disabled', true);
			var formdata = new FormData(this);
			
			CommonManager.ajaxupload(url, CategoryManager.handleFormResponse, 'post', formdata, 'json', {'current': current}); 
			return false;
			
		});
		
		/* _.on('click', '.addattr', function(e) {
			var current = $(this);
			
			var target = current.closest('.groupcontent');
			
			var group_count = target.attr('data-group-counter');
			var attr_count = target.attr('data-attr-counter');
			
			var clone = $('.container_attr', $('.container')).clone();
			
			count = parseInt(attr_count) - 1;
			
			$('.attr', clone).attr('name', 'attr['+group_count+']['+count+']');
			
			target.append(clone);

			target.attr('data-attr-counter', count);

		});
		
		_.on('click', '.removeattr', function(e) {
			$(this).closest('.container_attr').remove();
		});
		
		_.on('click', '.addgroup', function(e) {
			AttributeManager.categoryAttr();
		}); */
		
		AttributeManager.categoryAttr();
	},
	
	tabs: function(hash) {
		if(hash == 'attributes') {
			$('.nav-link').removeClass('active');
			$('.tab-pane', $('.tab-content')).removeClass('active show');

			$('#custom-tabs-one-profile-tab').addClass('active show');
			$('#custom-tabs-one-profile').addClass('active show');
		}
	}
}


var ChangePasswordManager = {
	handleFormResponse:function(response, extraData) {
		$('.err').remove();
		var current = extraData.current;
		current.find(":submit").html(current.find(":submit").attr('data-text')).prop('disabled', false);
		if(response.status == 'errors') {
			CommonManager.validateFields('.',response.errors, 'a');
			return false;
		} else if(response.status == 'error') {
			alert(response.msg);
			return false;
		} else if(response.status == 'success') {
			document.getElementById('change_password_form').reset();
			alert(response.msg);
		}
		
		
		//location.href = url+'/categories';
	},
	
	init: function() {
		_.on('submit', '#change_password_form', function(e) {
			$('.err').remove();
			var url = $('#change_password_form').attr('action');
			
			var current = $(this);
			current.find(":submit").html('Loading...').prop('disabled', true);
			var formdata = current.serialize();
			
			/* alert(url);
			
			let flag = true;
			if($('.password').val() == '') {
				$('.password').after('<small class="err help-block form-text text-danger ">Please enter password</small>');
				flag = false;
			}
			if($('.confirmpassword').val() == '') {
				$('.confirmpassword').after('<small class="err help-block form-text text-danger ">Please enter confirm password</small>');
				flag = false;
			}
			
			if(flag == false) {
				current.find(":submit").html(current.find(":submit").attr('data-text')).prop('disabled', false);
				return false;
			} */
			
			CommonManager.ajax(url, ChangePasswordManager.handleFormResponse, 'post', formdata, 'json', {'current': current});
			return false;
		});

	}
	
}


var ReelManager = {
	handleFormResponse:function(response, extraData) {
		$('.err').remove();
		var current = extraData.current;
		current.find(":submit").html(current.find(":submit").attr('data-text')).prop('disabled', false);
		if(response.status == 'errors') {
			CommonManager.validateFields('.',response.errors, 'a');
			return false;
		} else if(response.status == 'error') {
			alert(response.msg);
			return false;
		} else if(response.status == 'success') {
			document.getElementById('reel_form').reset();
			alert(response.msg);
			location.href = url+'/reels';
		}
		
		
		//location.href = url+'/categories';
	},
	
	init: function() {
		_.on('submit', '#reel_form', function(e) {
			$('.err').remove();
			var url = $('#reel_form').attr('action');
			
			var current = $(this);
			current.find(":submit").html('Loading...').prop('disabled', true);
			var formdata = new FormData(this);
			
			
			CommonManager.ajaxupload(url, ReelManager.handleFormResponse, 'post', formdata, 'json', {'current': current});
			return false;
		});

	}
	
}

var PostManager = {
	handleFormResponse:function(response, extraData) {
		$('.err').remove();
		var current = extraData.current;
		current.find(":submit").html(current.find(":submit").attr('data-text')).prop('disabled', false);
		if(response.status == 'errors') {
			CommonManager.validateFields('.',response.errors, 'a');
			return false;
		} else if(response.status == 'error') {
			alert(response.msg);
			return false;
		} else if(response.status == 'success') {
			document.getElementById('post_form').reset();
			alert(response.msg);
			location.href = url+'/post';
		}
		
		
		//location.href = url+'/categories';
	},
	
	init: function() {
		_.on('submit', '#post_form', function(e) {
			$('.err').remove();
			var url = $('#post_form').attr('action');
			
			var current = $(this);
			current.find(":submit").html('Loading...').prop('disabled', true);
			var formdata = new FormData(this);
			
			
			CommonManager.ajaxupload(url, PostManager.handleFormResponse, 'post', formdata, 'json', {'current': current});
			return false;
		});

	}
	
}

var BannerManager = {
	handleFormResponse:function(response, extraData) {
		$('.err').remove();
		var current = extraData.current;
		current.find(":submit").html(current.find(":submit").attr('data-text')).prop('disabled', false);
		if(response.status == 'errors') {
			CommonManager.validateFields('.',response.errors, 'a');
			return false;
		} else if(response.status == 'error') {
			alert(response.msg);
			return false;
		} else if(response.status == 'success') {
			document.getElementById('banner_form').reset();
			alert(response.msg);
			location.href = url+'/banner';
		}
		
		
		//location.href = url+'/categories';
	},
	
	init: function() {
		_.on('submit', '#banner_form', function(e) {
			$('.err').remove();
			var url = $('#banner_form').attr('action');
			
			var current = $(this);
			current.find(":submit").html('Loading...').prop('disabled', true);
			var formdata = new FormData(this);
			
			
			CommonManager.ajaxupload(url, BannerManager.handleFormResponse, 'post', formdata, 'json', {'current': current});
			return false;
		});

	}
	
}

var StoryManager = {
	handleFormResponse:function(response, extraData) {
		$('.err').remove();
		var current = extraData.current;
		current.find(":submit").html(current.find(":submit").attr('data-text')).prop('disabled', false);
		if(response.status == 'errors') {
			CommonManager.validateFields('.',response.errors, 'a');
			return false;
		} else if(response.status == 'error') {
			alert(response.msg);
			return false;
		} else if(response.status == 'success') {
			document.getElementById('story_form').reset();
			alert(response.msg);
			location.href = url+'/story';
		}
	},
	
	init: function() {
		_.on('submit', '#story_form', function(e) {
			$('.err').remove();
			var url = $('#story_form').attr('action');
			
			var current = $(this);
			//current.find(":submit").html('Loading...').prop('disabled', true);
			var formdata = new FormData(this);
			
			
			CommonManager.ajaxupload(url, StoryManager.handleFormResponse, 'post', formdata, 'json', {'current': current});
			return false;
		});

	}
	
}