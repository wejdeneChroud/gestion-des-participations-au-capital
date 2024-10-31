// Function to animate wave background based on scroll position
window.addEventListener('scroll', function() {
    let wave1 = document.getElementById('wave1');
    let wave2 = document.getElementById('wave2');
    let wave3 = document.getElementById('wave3');
    let wave4 = document.getElementById('wave4');
    
    let value = window.scrollY;

    wave1.style.backgroundPosition = 400 + value * 4 + 'px';
    wave2.style.backgroundPosition = 300 + value * -4 + 'px';
    wave3.style.backgroundPosition = 200 + value * 2 + 'px';
    wave4.style.backgroundPosition = 100 + value * -2 + 'px';
});

// Function to reset form
function resetForm() {
    document.getElementById("myForm").reset();
}

function openPopup(popupId) {
    var popup = document.getElementById(popupId);
    var overlay = document.getElementById('overlay');

    popup.style.display = "block";
    overlay.classList.remove('hidden');
}

function closePopup(popupId) {
    var popup = document.getElementById(popupId);
    var overlay = document.getElementById('overlay');
    popup.style.display = "none";
    overlay.classList.add('hidden');
}