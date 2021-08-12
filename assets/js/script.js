$(function () {
	const baseUrl =
		"http://localhost/oto/11MembuatSistemLoginLengkapDenganCodeigniter3/12-forgot-password/";

	// menu
	$("#addMenuButton").on("click", function () {
		$("#newMenuModalLabel").html("Add New Menu");
		$("#modalButton").html("Add");
		$(".modal-body form").attr("action", baseUrl + "menu");
		$("#id-user-menu").val("");
		$("#menu").val("");
		$("#menu").attr("required", false);
	});

	$(".edit-menu-button").on("click", function () {
		const id_user_menu = $(this).data("id");

		$("#newMenuModalLabel").html("Edit Menu");
		$("#modalButton").html("Edit");

		$(".modal-body form").attr("action", baseUrl + "menu/edit");
		$("#menu").attr("required", true);

		$.ajax({
			url: baseUrl + "menu/getEdit",
			data: {
				id: id_user_menu,
			},
			method: "post",
			dataType: "json",
			success: function (data) {
				// console.log(data);
				$("#id-user-menu").val(data.id_user_menu);
				$("#menu").val(data.menu);
			},
		});
	});
	// akhir menu

	// sub menu

	$("#addButtonSubmenu").on("click", function () {
		$("#newSubmenuModalLabel").html("Add New Submenu");
		$("#modalButton").html("Add");

		$(".modal-body form").attr("action", baseUrl + "menu/submenu");
		$("#title").attr("required", false);
		$("#idUserMenu").attr("required", false);
		$("#url").attr("required", false);
		$("#icon").attr("required", false);

		$("#idUserSubmenu").val("");
		$("#title").val("");
		$("#idUserMenu").val("placeholder");
		$("#url").val("");
		$("#icon").val("");
		$("#isActive").attr("checked", true);
	});

	$(".edit-submenu-button").on("click", function () {
		const idUserSubmenu = $(this).data("id");

		$("#newSubmenuModalLabel").html("Edit Submenu");
		$("#modalButton").html("Edit");

		$(".modal-body form").attr("action", baseUrl + "menu/editSubmenu");

		$("#title").attr("required", true);
		$("#idUserMenu").attr("required", true);
		$("#url").attr("required", true);
		$("#icon").attr("required", true);

		$.ajax({
			url: baseUrl + "menu/getEditSubmenu",
			method: "post",
			data: {
				idUserSubmenu: idUserSubmenu,
			},
			dataType: "json",
			success: function (data) {
				// console.log(data);
				$("#idUserSubmenu").val(data.id_user_sub_menu);
				$("#title").val(data.title);
				$("#idUserMenu").val(data.id_user_menu);
				$("#url").val(data.url);
				$("#icon").val(data.icon);
				if (data.is_active == 1) {
					$("#isActive").attr("checked", true);
				} else {
					$("#isActive").attr("checked", false);
				}
			},
		});
	});
	// akhir submenu

	// role
	$("#addRoleButton").on("click", function () {
		$("#newRoleModalLabel").html("Add New Role");
		$("#modalButton").html("Add");
		$(".modal-body form").attr("action", baseUrl + "admin/role");
		$("#role_id").val("");
		$("#role").val("");
		$("#role").attr("required", false);
	});

	$(".edit-role-button").on("click", function () {
		const role_id = $(this).data("id");

		$("#newRoleModalLabel").html("Edit Role");
		$("#modalButton").html("Edit");

		$(".modal-body form").attr("action", baseUrl + "admin/editRole");
		$("#role").attr("required", true);

		$.ajax({
			url: baseUrl + "admin/getEditRole",
			data: {
				roleId: role_id,
			},
			method: "post",
			dataType: "json",
			success: function (data) {
				// console.log(data);
				$("#role_id").val(data.role_id);
				$("#role").val(data.role);
			},
		});
	});
	// akhir role

	// checkbox access menu
	$(".form-check-input").on("change", function () {
		const roleId = $(this).data("roleid");
		const idUserMenu = $(this).data("idusermenu");

		$.ajax({
			url: baseUrl + "admin/changeAccessMenu",
			method: "post",
			data: {
				roleId: roleId,
				idUserMenu: idUserMenu,
			},
			// dataType: "json", // jika tidak dipakai maka matikan saja karena parameter success tidak akan jalan jika semua parameter tidak terpenuhi
			success: function () {
				document.location.href = baseUrl + "admin/roleAccess/" + roleId;
			},
		});
	});
	// akhir checkbox access menu

	// delete sweetallert
	$(".delete-button").on("click", function (e) {
		// yang pertama matikan dulu hrefnya, karena confrim default browser tidak akan menjalankan href jika tidak dikonfirmasi sedangkan sweetalert tetap akan menjalankan href saat alert muncul
		e.preventDefault();
		const href = $(this).attr("href"); // tombol hapus yang diklik akan diambil attribut hrefnya

		Swal.fire({
			title: "Are You Sure?",
			text: "this data will be deleted!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Delete",
		}).then((result) => {
			if (result.isConfirmed) {
				// Swal.fire("Deleted!", "Your file has been deleted.", "success");
				// yang akan menjalankan hrefnya bukan html tapi javascript
				document.location.href = href;
			}
		});
	});
	//akhir delete sweetallert

	//fitur nama pada choose file
	$(".custom-file-input").on("change", function () {
		let fileName = $(this).val().split("\\").pop();
		$(this).next(".custom-file-label").addClass("selected").html(fileName);
	});
	//akhir fitur nama pada choose file
});
