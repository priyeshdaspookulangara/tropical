$(document).ready(function() {
    // Check for sidebar state in local storage
    if (localStorage.getItem('sidebar-toggled') === 'true') {
        $("#wrapper").addClass("toggled");
    }

    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");

        // Save sidebar state to local storage
        if ($("#wrapper").hasClass("toggled")) {
            localStorage.setItem('sidebar-toggled', 'true');
        } else {
            localStorage.setItem('sidebar-toggled', 'false');
        }
    });
});