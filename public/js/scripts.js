toastr.options = {"preventDuplicates": true, closeButton: true}
if($(".flash-message").length) {
	let flash = ($(".flash-message"));
	let type = (flash.data('type') === 'danger') ? 'error' : flash.data('type');
	let dismiss = flash.data('dismiss');
	let position = flash.data('position');
	let closebutton = (flash.data('closebutton')) ? flash.data('closebutton') : true;
	let message = flash.html();
	let auto_dismiss = (dismiss === 1) ? 5000 : 0;
	toastr[type](message, null, {
		timeOut: auto_dismiss, 
		positionClass: "toast-"+position,
		closeButton: closebutton
	});
}

/**
 * Submit the signnup form with ajax
 */
$("#SignupForm").on("submit", function(e){
	e.preventDefault();
  
	$.ajax({
		type: "POST",
		dataType: "json",
		url: baseUrl + "signup",
		data: $(this).serialize(),
		beforeSend: function() {
			$("button.spin").attr("data-send", "true");
			$("button.spin span").hide();
		},
		complete: function() {
			$("button.spin").attr("data-send", "false");
			$("button.spin span").show();
		},
		success: function(response){
			if (response.type === 'success') {
				toastr["success"](response.message, null, {positionClass: "toast-bottom-right"});
				setTimeout(function() {
					window.location.replace(response.redirect);
				}, 500 );
				// Exit early to prevent further processing.
				return;
			}
	
			// Only gets here if the response is not a 'success'
			let errorMsg = '';
			// Check if message is an object before iterating
			if (typeof response.message === 'object') {
				$.each(response.message, function(field, msg) {
					errorMsg += msg + "<br>"; // Extract each error message
				});
			} else {
				errorMsg = response.message;
			}
			$("#message").html(errorMsg);
			toastr[response.type](errorMsg, null, {positionClass: "toast-bottom-right", timeOut: 0});
		}
	});
});

/**
 * Submit login form with ajax
 */
$("#LoginForm").on("submit", function(e){
	e.preventDefault();
  
	$.ajax({
		type: "POST",
		dataType: "json",
		url: baseUrl + "login",
		data: $(this).serialize(),
		beforeSend: function() {
			$("button.spin").attr("data-send", "true");
			$("button.spin span").hide();
		},
		complete: function() {
			$("button.spin").attr("data-send", "false");
			$("button.spin span").show();
		},
		success: function(response){
			if (response.type === 'success') {
				toastr["success"](response.message, null, {positionClass: "toast-bottom-right"});
				setTimeout(function() {
					window.location.replace(response.redirect);
				}, 500 );
				// Exit early to prevent further processing.
				return;
			}
	
			// Only gets here if the response is not a 'success'
			let errorMsg = '';
			// Check if message is an object before iterating
			if (typeof response.message === 'object') {
				$.each(response.message, function(field, msg) {
					errorMsg += msg + "<br>"; // Extract each error message
				});
			} else {
				errorMsg = response.message;
			}
			$("#message").html(errorMsg);
			toastr[response.type](errorMsg, null, {positionClass: "toast-bottom-right", timeOut: 0});
		}
	});
});

/**
 * Create task
 */
$("#CreateTask").on("submit", function(e){
	e.preventDefault();
  
	$.ajax({
		type: "POST",
		dataType: "json",
		url: baseUrl + "admincp/task/create",
		data: $(this).serialize(),
		beforeSend: function() {
			$("button.spin").attr("data-send", "true");
			$("button.spin span").hide();
		},
		complete: function() {
			$("button.spin").attr("data-send", "false");
			$("button.spin span").show();
		},
		success: function(response){
			if (response.type === 'success') {
				toastr["success"](response.message, null, {positionClass: "toast-bottom-right"});
				setTimeout(function() {
					window.location.replace(response.redirect);
				}, 500 );
				// Exit early to prevent further processing.
				return;
			}
	
			// Only gets here if the response is not a 'success'
			let errorMsg = '';
			// Check if message is an object before iterating
			if (typeof response.message === 'object') {
				$.each(response.message, function(field, msg) {
					errorMsg += msg + "<br>"; // Extract each error message
				});
			} else {
				errorMsg = response.message;
			}
			$("#message").html(errorMsg);
			toastr[response.type](errorMsg, null, {positionClass: "toast-bottom-right", timeOut: 0});
		}
	});
});
  
/**
 * Edit task
 * @var id Task id
 */
$("#EditTask").on("submit", function(e){
	e.preventDefault();
	let id = $(this).data('id');
  
	$.ajax({
		type: "POST",
		dataType: "json",
		url: baseUrl + "admincp/task/update/" + id,
		data: $(this).serialize(),
		beforeSend: function() {
			$("button.spin").attr("data-send", "true");
			$("button.spin span").hide();
		},
		complete: function() {
			$("button.spin").attr("data-send", "false");
			$("button.spin span").show();
		},
		success: function(response){
			if (response.type === 'success') {
				toastr["success"](response.message, null, {positionClass: "toast-bottom-right"});
				setTimeout(function() {
					window.location.replace(response.redirect);
				}, 500 );
				// Exit early to prevent further processing.
				return;
			}
	
			// Only gets here if the response is not a 'success'
			let errorMsg = '';
			// Check if message is an object before iterating
			if (typeof response.message === 'object') {
				$.each(response.message, function(field, msg) {
					errorMsg += msg + "<br>"; // Extract each error message
				});
			} else {
				errorMsg = response.message;
			}
			$("#message").html(errorMsg);
			toastr[response.type](errorMsg, null, {positionClass: "toast-bottom-right", timeOut: 0});
		}
	});
});

/**
 * Delete task
 * @var id Task id
 */
function delete_task(id) {
    if (confirm("Are you sure you want to delete this?")) {
        $.ajax({
            url: baseUrl + 'admincp/task/delete/' + id,
            type: 'DELETE',
            dataType: 'json',
            data: { csrf_token: requestToken }, // Send the CSRF token 
            success: function (response) {
                // Remove the deleted task
                $("table tr#item-" + id).remove();
                
                // Show success notification
                toastr[response.type](response.message, null, { positionClass: "toast-bottom-right", closeButton: true, timeOut: 3000 });

                // **Update the CSRF token for future requests**
                requestToken = response.csrf_token; // Ensure backend sends back the new token
            }
        });
    }
    return false;
}

/**
 * Add task
 */
$("#CreateTaskCategory").on("submit", function(e){
	e.preventDefault();
  
	$.ajax({
		type: "POST",
		dataType: "json",
		url: baseUrl + "admincp/task/category/create",
		data: $(this).serialize(),
		beforeSend: function() {
			$("button.spin").attr("data-send", "true");
			$("button.spin span").hide();
		},
		complete: function() {
			$("button.spin").attr("data-send", "false");
			$("button.spin span").show();
		},
		success: function(response){
			if (response.type === 'success') {
				toastr["success"](response.message, null, {positionClass: "toast-bottom-right"});
				setTimeout(function() {
					window.location.replace(response.redirect);
				}, 500 );
				// Exit early to prevent further processing.
				return;
			}
	
			// Only gets here if the response is not a 'success'
			let errorMsg = '';
			// Check if message is an object before iterating
			if (typeof response.message === 'object') {
				$.each(response.message, function(field, msg) {
					errorMsg += msg + "<br>"; // Extract each error message
				});
			} else {
				errorMsg = response.message;
			}
			$("#message").html(errorMsg);
			toastr[response.type](errorMsg, null, {positionClass: "toast-bottom-right", timeOut: 0});
		}
	});
});

/**
 * Edit task category
 * @var id Categoory id
 */
$("#EditTaskCategory").on("submit", function(e){
	e.preventDefault();
	let id = $(this).data('id');
  
	$.ajax({
		type: "POST",
		dataType: "json",
		url: baseUrl + "admincp/task/category/update/" + id,
		data: $(this).serialize(),
		beforeSend: function() {
			$("button.spin").attr("data-send", "true");
			$("button.spin span").hide();
		},
		complete: function() {
			$("button.spin").attr("data-send", "false");
			$("button.spin span").show();
		},
		success: function(response){
			if (response.type === 'success') {
				toastr["success"](response.message, null, {positionClass: "toast-bottom-right"});
				setTimeout(function() {
					window.location.replace(response.redirect);
				}, 500 );
				// Exit early to prevent further processing.
				return;
			}
	
			// Only gets here if the response is not a 'success'
			let errorMsg = '';
			// Check if message is an object before iterating
			if (typeof response.message === 'object') {
				$.each(response.message, function(field, msg) {
					errorMsg += msg + "<br>"; // Extract each error message
				});
			} else {
				errorMsg = response.message;
			}
			$("#message").html(errorMsg);
			toastr[response.type](errorMsg, null, {positionClass: "toast-bottom-right", timeOut: 0});
		}
	});
});

/**
 * Delete task category
 * @var id Category id
 */
function delete_task_category (id) { 
	if(confirm("Are you sure you want to delete this?")) {
		$.ajax({
			dataType: 'json',
			url : baseUrl + 'admincp/task/category/delete/' + id,
			type: 'DELETE',
			data: {csrf_token: requestToken},
			success: function (response) {
				$(".category-table tr#category-item-"+id).remove();
				toastr[response.type](response.message, null, {positionClass: "toast-bottom-right", closeButton: true, timeOut: 3000});

				// **Update the CSRF token for future requests**
                requestToken = response.csrf_token; // Ensure backend sends back the new token
			}
		});
	}
    return false;
}

/**
 * Search and filter functionality to find tasks easily
 */
function filterTask() {
    var input, filter, table, tr, td1, td2, i, txtValue1, txtValue2;
    input = document.getElementById("searchInput");
    filter = input.value.toLowerCase();
    table = document.getElementById("tasktable");
    tr = table.getElementsByTagName("tr");
    
    for (i = 1; i < tr.length; i++) { // Skip header row
        td1 = tr[i].getElementsByTagName("td")[1]; // First column (Name)
        td2 = tr[i].getElementsByTagName("td")[2]; // Second column (Category)
        
        if (td1 && td2) {
            txtValue1 = td1.textContent || td1.innerText;
            txtValue2 = td2.textContent || td2.innerText;
            
            // Check if the filter text matches either column
            if (txtValue1.toLowerCase().indexOf(filter) > -1 || txtValue2.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }
}

/**
 * Show/Hide password input
 */
$(document).ready(function() {
    $('.password-visibility').click(function() {
        var icon = $(this).find('i'); 
        var password = icon.parent().prev(); 
        if (password.attr('type') === 'password') {
            password.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            password.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});

/**
 * Important!
 * This attaches a global event handler that listens for the completion of any AJAX request.
 */
$(document).ajaxComplete(function(event, xhr, settings) {

    // Try to retrieve the new CSRF token from the response header.
    // Assume that your server sends the new CSRF token in a header named "X-CSRF-TOKEN".
    var newToken = xhr.getResponseHeader('X-CSRF-TOKEN');

    // Check if the token exists.
    if (newToken) {
        // Find the hidden input field in your form that holds the CSRF token,
        // and update its value with the new token from the server.
        $('input[name="csrf_token"]').val(newToken);
    }
});

