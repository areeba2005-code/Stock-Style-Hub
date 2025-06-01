const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

// Function to set the active class based on the current URL
function setActiveSidebarLink() {
    const currentPath = window.location.pathname; // e.g., "/WareHouse.html" or "/dashboard.html"
    // Extract just the filename from the path
    const currentFileName = currentPath.substring(currentPath.lastIndexOf('/') + 1);

    allSideMenu.forEach(item => {
        const li = item.parentElement;
        const linkHref = item.getAttribute('href');
        // Extract just the filename from the link's href
        const linkFileName = linkHref ? linkHref.substring(linkHref.lastIndexOf('/') + 1) : '';

        // Remove active class from all items first
        li.classList.remove('active');

        // If the link's filename matches the current page's filename, add the active class
        if (linkFileName === currentFileName) {
            li.classList.add('active');
        }
    });
}

// Call the function when the DOM is fully loaded.
// This is crucial for when the page initially loads or reloads.
document.addEventListener('DOMContentLoaded', setActiveSidebarLink);

// Original click event listener (kept for immediate visual feedback on click
// before a potential page reload, especially if target="_blank" is removed)
allSideMenu.forEach(item=> {
    const li = item.parentElement;

    item.addEventListener('click', function () {
        // Remove active class from all other menu items
        allSideMenu.forEach(i=> {
            i.parentElement.classList.remove('active');
        })
        // Add active class to the clicked item
        li.classList.add('active');
    })
});


// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
})


const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
    if(window.innerWidth < 576) {
        e.preventDefault();
        searchForm.classList.toggle('show');
        if(searchForm.classList.contains('show')) {
            searchButtonIcon.classList.replace('bx-search', 'bx-x');
        } else {
            searchButtonIcon.classList.replace('bx-x', 'bx-search');
        }
    }
})


if(window.innerWidth < 768) {
    sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
    searchButtonIcon.classList.replace('bx-x', 'bx-search');
    searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
    if(this.innerWidth > 576) {
        searchButtonIcon.classList.replace('bx-x', 'bx-search');
        searchForm.classList.remove('show');
    }
})



const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
    if(this.checked) {
        document.body.classList.add('dark');
    } else {
        document.body.classList.remove('dark');
    }
})