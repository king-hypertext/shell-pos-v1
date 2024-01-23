(function () {
	console.log("js loaded");
	setInterval(() => {
		document.querySelector("[data-date-time]").innerHTML =
			new Date().toUTCString();
	}, 1000);
})();
$(document).ready(function () {
	$(document).on('click', 'button#nav-toggler', () => {
		$(".side-wrapper").toggleClass("show");
		$(".back-drop").toggleClass("show");
		$("button#nav-toggler > i").toggleClass("fas fa-bars");
		$("button#nav-toggler > i").toggleClass("fas fa-x");
	});
	$(document).on('click', '#back-drop', () => {
		$(".side-wrapper").toggleClass("show");
		$(".back-drop").toggleClass("show");
		$("button#nav-toggler > i").toggleClass("fas fa-bars");
		$("button#nav-toggler > i").toggleClass("fas fa-x");
	});
	// dropdown icon change
	$('.nav-item > a.dropdown[data-bs-toggle="collapse"]')
		.on("click", (e) => {
			e = e.currentTarget.children[1].children[0].classList;
			// e.add("")
			e.toggle("fa-chevron-right");
			e.toggle("fa-chevron-down");
		});
	document.querySelectorAll(".nav-item > a").forEach((target) => {
		new mdb.Ripple(target, {
			rippleColor: "#fff",
			rippleDuration: "1000ms",
		});
	});

});
