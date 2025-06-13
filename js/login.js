let failedEmail = "";
let failedPass = "";
let messageError= "";

$(function() {
	$(document).on("contextmenu", function(e) {
		e.preventDefault(); // Evita que se muestre el menú contextual
	});
	
	$(".form-login").on("submit", function(event) {
		event.preventDefault();
		let email= $('#email').val().trim();
		let password= $('#password').val().trim();
		if(password.length< 8){
			$(".container-alert").addClass("container-alert-danger");
			$(".alert-text").html("Contraseña muy corta");
			return;
		}
		
		if(email== "" || password== "") {
			$(".container-alert").addClass("container-alert-danger");
			$(".alert-text").html("Ningún campo puede estar vacio");
		}else{
			$.post('../controller/controller_forms.php', {
				'type-form': 'login',
				email: email,
				password: password
			}, function(answer) {
				if(answer.state== 1){
					window.location.href= 'dashboard.php';			
				}else{
					failedEmail= email;
					failedPass= password;
					messageError= answer.message;
					$(".input").addClass("input-error");
					$(".container-alert").addClass("container-alert-danger");
					$(".alert-text").html(messageError);
				}
			});
		}
	});
	
	$(".input").on("input", function(){
		let currentEmail= $("#email").val().trim();
		let currentPass= $("#password").val().trim();		
		
		if(currentEmail== failedEmail && currentPass== failedPass){
			$(".input").addClass("input-error");
			$(".container-alert").removeClass("container-alert-success").addClass("container-alert-danger");
			$(".alert-text").html(messageError);
		}else{
			$(".input").removeClass('input-error');
			$(".container-alert").removeClass("container-alert-danger container-alert-success");
			$(".alert-text").html("");
		}		
	});		
	
    //Script Registro	
	$("#signup-button").on("click", function(event){
		event.preventDefault();
		let inputsEmpty= false;
		let firstInputEmpty= null;
		
		const inputs= [
			"#names", 
			"#last-names", 
			"#type-document",
			"#id-number",
			"#number-phone",
			"#address",
			"#email-user",
			"#password-user",
			"#re-password-user"
		];
		
		inputs.forEach(function(idInput){
			if($(idInput).val().trim()=== ""){
				inputsEmpty= true;
				
				$(idInput).addClass("input-error");	
				if(!firstInputEmpty){
					firstInputEmpty= idInput;
				}
			}else{
				$(idInput).removeClass("input-error");
			}
		})
		
		if(inputsEmpty){
			$(".container-alert").addClass("container-alert-danger");
			$(".alert-text").html("Todos los campos son requeridos");
			$(firstInputEmpty).focus();
			
			$(".form-control").on("input", function(){
				$(this).removeClass("input-error");
			});
			return;
		}
		
		let password= $("#password-user").val().trim();
		let rePassword= $("#re-password-user").val().trim();

		if(password !== rePassword){
			$(".container-alert").removeClass("container-alert-success").addClass("container-alert-danger");
			$(".alert-text").html("Las contraseñas no coinciden");
			return; 
		} else {
			$(".container-alert").removeClass("container-alert-danger").addClass("container-alert-success");
			$(".alert-text").html("");
		}
			
			
		
		$.post("../Controller/controller_forms.php", {
			"type-form": "register",
			names: $("#names").val().trim(),
			lastNames: $("#last-names").val().trim(),
			typeDocument: $("#type-document").val().trim(),
			idNumber: $("#id-number").val().trim(),
			numberPhone: $("#number-phone").val().trim(),
			address: $("#address").val().trim(),
			email: $("#email-user").val().trim(),
			password: $("#password-user").val().trim(),
		}, function(answer){
			if(answer.state== 1){
				//window.location.href= "../2_view/login.php";
				$(".container-alert").removeClass("container-alert-danger").addClass("container-alert-success");
				$(".alert-text").html(answer.message);
			}else{
				//window.location.href= "../2_view/login.php";
				$(".container-alert").addClass("container-alert-danger");
				$(".alert-text").html(answer.message);
			}
		});
	});
	
	$(".btns-close").on("click", function(){
		$(".container-alert").removeClass("container-alert-success container-alert-danger");
		$(".alert-text").html("");
		$(".form-control").removeClass("input-error");
	});	
	
	$(".remember_password").on("click", function(){		
		
		let email= $("#email").val().trim();
		
		function contieneCorreo(email) {
			const regexCorreo = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/;
			return regexCorreo.test(email);
		}

		
		if(!contieneCorreo(email)){
			$(".container-alert").addClass("container-alert-danger");
			$(".alert-text").html("Ingrese un correo valido");
			$("#email").addClass("input-error");
			$("#email").focus();
			return;
		};
	
		if(email){
			$.post("../Controller/controller_forms.php",{
				"type-form": "recovery_password",
				email: email
			}, function(answer){
				if(answer.state== 1){
					window.location.href= "send_successful.php";					
				}else{
					$(".container-alert").removeClass("container-alert-success container-alert-danger");
					$(".container-alert").addClass("container-alert-danger");
					$(".alert-text").html(answer.message);					
				}				
			});
		}else{
			$(".container-alert").addClass("container-alert-danger");
			$(".alert-text").html("Caja de texto sin datos");
		}		
	});
	
	
});