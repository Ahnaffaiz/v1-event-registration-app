/* Get into full screen */
function GoInFullscreen(element) {
    if (element.requestFullscreen) element.requestFullscreen();
    else if (element.mozRequestFullScreen) element.mozRequestFullScreen();
    else if (element.webkitRequestFullscreen) element.webkitRequestFullscreen();
    else if (element.msRequestFullscreen) element.msRequestFullscreen();
}

/* Get out of full screen */
function GoOutFullscreen() {
    if (document.exitFullscreen) document.exitFullscreen();
    else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
    else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
    else if (document.msExitFullscreen) document.msExitFullscreen();
}

/* Is currently in full screen or not */
function IsFullScreenCurrently() {
    var full_screen_element =
        document.fullscreenElement ||
        document.webkitFullscreenElement ||
        document.mozFullScreenElement ||
        document.msFullscreenElement ||
        null;

    // If no element is in full-screen
    if (full_screen_element === null) return false;
    else return true;
}

function getFullscreen() {
    const screenElement = document.getElementById("fullscreen-container");
    const maximizeIcon = document.getElementById("fullscreen-icon-maximize");
    const minimizeIcon = document.getElementById("fullscreen-icon-minimize");

    if (IsFullScreenCurrently()) {
        GoOutFullscreen();
        // Show maximize icon and hide minimize icon
        maximizeIcon.classList.remove("hidden");
        minimizeIcon.classList.add("hidden");
    } else {
        GoInFullscreen(screenElement);
        // Show minimize icon and hide maximize icon
        minimizeIcon.classList.remove("hidden");
        maximizeIcon.classList.add("hidden");
    }
}
