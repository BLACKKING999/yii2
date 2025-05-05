// Sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar visibility
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const mainHeader = document.querySelector('.main-header');
    const overlay = document.querySelector('.overlay');
    
    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            content.classList.toggle('sidebar-active');
            mainHeader.classList.toggle('sidebar-active');
            overlay.classList.toggle('active');
        });
    }
    
    // Handle overlay click (close sidebar)
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            content.classList.remove('sidebar-active');
            mainHeader.classList.remove('sidebar-active');
            overlay.classList.remove('active');
        });
    }
    
    // Simple dropdown functionality for sidebar menus
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the target submenu
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            // Toggle this submenu
            if (targetElement) {
                targetElement.classList.toggle('show');
                
                // Close other submenus
                const allSubmenus = document.querySelectorAll('.sidebar-nav .collapse');
                allSubmenus.forEach(function(submenu) {
                    if (submenu !== targetElement && submenu.classList.contains('show')) {
                        submenu.classList.remove('show');
                    }
                });
            }
        });
    });
    
    // Check current URL and highlight active menu items
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('.sidebar-nav a:not(.dropdown-toggle)');
    
    menuLinks.forEach(function(link) {
        if (link.getAttribute('href') === currentPath || 
            link.getAttribute('href') === window.location.href) {
            
            // Mark the link as active
            link.parentElement.classList.add('active');
            
            // If inside a submenu, expand the submenu
            const parentSubmenu = link.closest('.collapse');
            if (parentSubmenu) {
                parentSubmenu.classList.add('show');
            }
        }
    });
});
