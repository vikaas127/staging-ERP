<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $isRTL = (is_rtl() ? 'true' : 'false'); ?>

<!DOCTYPE html>
<html lang="<?php echo e($locale); ?>" dir="<?php echo ($isRTL == 'true') ? 'rtl' : 'ltr' ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?php echo isset($title) ? $title : get_option('companyname'); ?></title>

    <?php echo app_compile_css(); ?>
    <?php render_admin_js_variables(); ?>
    <link rel="manifest" href="<?= base_url('assets/pwa/manifest/user.json') ?>?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/assets/pwa/service-worker.js')
                .then((registration) => {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch((error) => {
                    console.error('Service Worker registration failed:', error);
                });
        }
    </script>
    <script>
    var totalUnreadNotifications = <?php echo e($current_user->total_unread_notifications); ?>,
        proposalsTemplates = <?php echo json_encode(get_proposal_templates()); ?>,
        contractsTemplates = <?php echo json_encode(get_contract_templates()); ?>,
        billingAndShippingFields = ['billing_street', 'billing_city', 'billing_state', 'billing_zip', 'billing_country',
            'shipping_street', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'
        ],
        isRTL = '<?php echo e($isRTL); ?>',
        taskid, taskTrackingStatsData, taskAttachmentDropzone, taskCommentAttachmentDropzone, newsFeedDropzone,
        expensePreviewDropzone, taskTrackingChart, cfh_popover_templates = {},
        _table_api;
    </script>
    <div id="install-notification" style="display:none;" class="install-notification">
        <p>Install our app for a better experience!</p>
        <button id="install-button" class="install-btn">Install</button>
        <button id="close-notification" class="close-btn">&times;</button>
    </div>
   
    <?php app_admin_head(); ?>
    <script>
        let deferredPrompt;

        // Listen for the beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault(); // Prevent the default prompt
            deferredPrompt = e; // Save the event for later use
            document.getElementById('install-notification').style.display = 'flex'; // Show the notification
        });

        // Handle the install button click
        document.getElementById('install-button').addEventListener('click', () => {
            if (deferredPrompt) {
                deferredPrompt.prompt(); // Show the install prompt
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null; // Reset the prompt variable
                });
            }
            document.getElementById('install-notification').style.display = 'none'; // Hide the notification
        });

        // Handle the close button click
        document.getElementById('close-notification').addEventListener('click', () => {
            document.getElementById('install-notification').style.display = 'none'; // Hide the notification
        });

        // Check if the app is already installed
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('App is already installed');
            document.getElementById('install-notification').style.display = 'none'; // Hide the notification
        }

    </script>

    <style>
        .install-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            padding: 15px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-family: Arial, sans-serif;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: slide-in 0.5s ease-in-out;
        }

        .install-notification p {
            margin: 0 0 10px;
            font-size: 16px;
            color: #333;
            text-align: center;
        }

        .install-btn {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .install-btn:hover {
            background: #0056b3;
        }

        .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            background: none;
            border: none;
            font-size: 16px;
            color: #888;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #333;
        }

        @keyframes slide-in {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Custom CSS */
        .panel-body h4 {
            color: #000;
        }
        body #menu.sidebar{
            height: 100vh;
            z-index: 9999;
        }
        #side-menu>li:first-child {
            position: sticky;
            top: 0;
            z-index: 1;
        }
        #side-menu li a{
            padding: 13px 20px;
        }
        .admin #side-menu li .nav-second-level li{
            padding-left: 0;
        }
        body #side-menu li .nav-second-level li.active a {
            background: #093858 !important;
            color: #fff !important;
            margin: 0;
            padding: 13px 20px;
            display: block;
        }
        body #side-menu li .nav-second-level li.active a i.menu-icon{
            color: #39c529;
        }

     
     
        body #wrapper .dataTables_wrapper .table a:hover{
            color: #39c529;
        }
        body #wrapper .dataTables_wrapper img.images_w_table {
            width: auto;
            height: 55px;
        }

        body #wrapper .dataTables_wrapper span.label.label-tag {
            background: #002a46;
            color: #fff;
            border-color: #002a46;
        }
        input[type="radio"]:checked + label:after, input[type="checkbox"]:checked + label:after, .radio-primary input[type=radio] + label::after{
            background: #39c529 !important
        }
        
        .pagination > .active > a{
            background: #002a46
        }

        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover, .nav-tabs > li > a:focus, .nav-tabs > li > a:hover {
            color: #39c529;
            border-color: #39c529;
        }
        .app_dt_empty table thead{
            opacity: 1;
        }
        #setup-menu .customizer-heading {
            right: unset;
        }
        #setup-menu .close-customizer {
            margin-top: 9px;
            margin-right: 0;
        }
        .sidebar #setup-menu .arrow {
            transform: rotate(180deg);
            position: absolute;
            right: 5px;
            top: 13px;
        }
        ul#setup-menu > li:first-child {
            background: #093858 !important;
        }
        ul#setup-menu > li:first-child a:hover {
            background: transparent !important;
        }
        body #menu, body #setup-menu-wrapper{
            z-index: 99999;
        }
        .nav>li{
            position: static
        }
        ul.nav.nav-second-level {
            position: relative !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            display: none; /* Default hidden */
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }
        ul.nav.nav-second-level.show {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* ul.nav.tech-setup-nav-second-level {
  
          background: #002036;
          padding: 10px 0;
          margin-top: -46px;
          left: 245px;
          top: 0%;
          width: 240px;
          transition: none;
          background: #002036;
          padding: 10px 0;
          margin-top: 60px;
          z-index: 1999999;
           position: fixed !important;

        } */

        

        @media (min-width: 1200px){
            body div#header {
                position: fixed;
                top: 0;
                right: 0;
                width: calc(100% - 240px);
            }
            div#wrapper .content {
                margin-top: 62px;
                padding: 15px;
                background: #f2f2f2;
            }
        }

    </style>
<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("Script loaded!");

    // List of menu classes
    const menuClasses = [
        "menu-item-PMS", "menu-item-CRM", "menu-item-warehouse", "menu-item-manufacturing",
        "menu-item-purchase", "menu-item-approvals", "menu-item-accounting", "menu-item-account-planning",
        "menu-item-quickbooks_integration", "menu-item-ma", "menu-item-omni_sales", "menu-item-hr_profile",
        "menu-item-hr_payroll", "menu-item-timesheets", "menu-item-reports", "menu-item-recruitment",
        "menu-item-expenses", "menu-item-fleet", "menu-item-fixed_equipment", "menu-item-support",
        "menu-item-utilities"
    ];

    // Function to close all submenus
    function closeSubmenus() {
        document.querySelectorAll(".nav-second-level").forEach(ul => {
            ul.classList.remove("show"); // Bootstrap class for open menus
            ul.classList.add("collapse");
            ul.style.display = "none";
            ul.setAttribute("aria-expanded", "false");
        });

        menuClasses.forEach(menuClass => {
            document.querySelectorAll(`.${menuClass} > a`).forEach(menu => {
                menu.classList.remove("active");
            });
        });
    }

    // Check if we need to close submenus after redirection
    if (sessionStorage.getItem("closeSubmenu") === "true") {
        console.log("Closing submenu after redirection...");
        closeSubmenus();
        sessionStorage.removeItem("closeSubmenu"); // Remove flag after execution
    }

    // Detect when a submenu item is clicked
    document.querySelectorAll(".nav-second-level a").forEach(link => {
        link.addEventListener("click", function () {
            console.log("Submenu link clicked:", this.href);
            sessionStorage.setItem("closeSubmenu", "true"); // Set flag before redirecting
        });
    });

    // Submenu Toggle (On Click)
    menuClasses.forEach(menuClass => {
        document.querySelectorAll(`.${menuClass} > a`).forEach(menuItem => {
            menuItem.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent default navigation for parent link
                let submenu = this.nextElementSibling;

                if (submenu && submenu.classList.contains("nav-second-level")) {
                    let isOpen = submenu.classList.contains("show");

                    // Close all submenus before opening the clicked one
                    closeSubmenus();

                    if (!isOpen) {
                        submenu.classList.add("show");
                        submenu.classList.remove("collapse");
                        submenu.style.display = "block";
                        submenu.setAttribute("aria-expanded", "true");
                        this.classList.add("active");
                    }
                }
            });
        });
    });

});
</script> -->
<script>
// document.addEventListener("DOMContentLoaded", function () {
//     console.log("Script loaded!");

//     // Function to close all submenus
//     function closeSubmenus() {
//         document.querySelectorAll(".nav-second-level").forEach(ul => {
//             ul.classList.remove("show"); // Bootstrap class for open menus
//             ul.classList.add("collapse");
//             ul.style.display = "none";
//             ul.setAttribute("aria-expanded", "false");
//         });

//         document.querySelectorAll(".nav > li > a").forEach(menu => {
//             menu.classList.remove("active");
//         });
//     }

//     // Check if we need to close submenus after redirection
//     if (sessionStorage.getItem("closeSubmenu") === "true") {
//         console.log("Closing submenu after redirection...");
//         closeSubmenus();
//         sessionStorage.removeItem("closeSubmenu"); // Remove flag after execution
//     }

//     // Detect when a submenu item is clicked (to close on redirection)
//     document.querySelectorAll(".nav-second-level a").forEach(link => {
//         link.addEventListener("click", function () {
//             console.log("Submenu link clicked:", this.href);
//             sessionStorage.setItem("closeSubmenu", "true"); // Set flag before redirecting
//         });
//     });

//     // Submenu Toggle (On Click)
//     // document.querySelectorAll(".nav > li > a").forEach(menuItem => {
//     //     menuItem.addEventListener("click", function (event) {
//     //         let submenu = this.nextElementSibling;

//     //         if (submenu && submenu.classList.contains("nav-second-level")) {
//     //             event.preventDefault(); // Prevent default navigation for parent link

//     //             let isOpen = submenu.classList.contains("show");

//     //             // Pehle sabhi submenus band karte hain
//     //             document.querySelectorAll(".nav-second-level").forEach(sub => {
//     //                 sub.classList.remove("show");
//     //                 sub.classList.add("collapse");
//     //                 sub.style.display = "none";
//     //                 sub.setAttribute("aria-expanded", "false");
//     //             });

//     //             document.querySelectorAll(".nav > li > a").forEach(item => {
//     //                 item.classList.remove("active");
//     //             });

//     //             // Ab sirf clicked wala submenu open hoga
//     //             if (!isOpen) {
//     //                 submenu.classList.add("show");
//     //                 submenu.classList.remove("collapse");
//     //                 submenu.style.display = "block";
//     //                 submenu.setAttribute("aria-expanded", "true");
//     //                 this.classList.add("active");
//     //             }
//     //         }
//     //     });
//     // });

// });
</script>

<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("Setup Menu Script Loaded!");

    // Function to close all submenus except the one being opened
    function closeSubmenus(exceptSubmenu = null) {
        document.querySelectorAll(".nav .tech-setup-nav-second-level .nav-second-level").forEach(submenu => {
            if (submenu !== exceptSubmenu) {
                submenu.style.display = "none"; // Hide all submenus
                submenu.classList.remove("show");
                submenu.setAttribute("aria-expanded", "false");
            }
        });

        document.querySelectorAll(".tech-setup-sub-menu-item > a").forEach(menu => {
            if (menu.nextElementSibling !== exceptSubmenu) {
                menu.classList.remove("active");
            }
        });
    }

    // Handle submenu toggling
    document.querySelectorAll(".tech-setup-sub-menu-item > a").forEach(menuItem => {
        menuItem.addEventListener("click", function (event) {
            let submenu = this.nextElementSibling;
            console.log("Clicked Menu Item:", this);
    console.log("Found Submenu:", submenu);

    if (!submenu) {
        console.error("Submenu not found! Check your HTML structure.");
        return;
    }

            if (submenu && submenu.classList.contains("nav-second-level")) {
                event.preventDefault(); // Prevent navigation for expandable items

                let isOpen = submenu.style.display === "block";

                // Close all other submenus before opening the clicked one
                closeSubmenus(submenu);

                if (!isOpen) {
                    submenu.style.display = "block"; // Show submenu
                    submenu.classList.add("show");
                    submenu.setAttribute("aria-expanded", "true");
                    this.classList.add("active");

                    // Fix submenu positioning
                    let parentRect = this.getBoundingClientRect();
                    let submenuWidth = submenu.offsetWidth || 240; // Default width if offsetWidth is 0
                    let windowWidth = window.innerWidth;

                    // Ensure submenu does not overflow right side
                    if (parentRect.right + submenuWidth < windowWidth) {
                        submenu.style.left = `${parentRect.right}px`;
                    } else {
                        submenu.style.left = `${parentRect.left - submenuWidth}px`;
                    }

                    submenu.style.top = `${parentRect.bottom}px`; // Position below the menu item
                }
            }
        });
    });

    // Close submenus after page reload if needed
    if (sessionStorage.getItem("closeSubmenu") === "true") {
        console.log("Closing submenu after redirection...");
        closeSubmenus();
        sessionStorage.removeItem("closeSubmenu"); // Remove flag after execution
    }

    // Detect submenu item clicks and store session data to close after reload
    document.querySelectorAll(".tech-setup-nav-second-level >.tech-setup-sub-menu-item > a").forEach(link => {
        link.addEventListener("click", function () {
            console.log("Submenu link clicked:", this.href);
            sessionStorage.setItem("closeSubmenu", "true"); // Set flag before redirection
        });
    });

    // Close submenu when clicking outside
    document.addEventListener("click", function (event) {
        let isClickInside = event.target.closest(".tech-setup-sub-menu-item");
        if (!isClickInside) {
            closeSubmenus();
        }
    });
});

</script>
 <style>
  .tech-setup-nav-second-level {
    display: none;
    position: absolute;
    background: white;
    border: 1px solid #ddd;
    min-width: 200px;
    z-index: 1000;
}

.tech-setup-nav-second-level.show {
    display: block !important;
}

.tech-setup-sub-menu-item > a.active {
    font-weight: bold;
    color: #007bff;
}

</style> -->

<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("Script loaded!");

    // List of menu classes to target
    let menuClasses = [
        "menu-item-settings", "menu-item-elite_custom_js_css", "menu-item-knowledge-base",
        "menu-item-custom-fields", "menu-item-email-templates", "menu-item-si_custom_status_setup_menu",
        "menu-item-estimate_request", "menu-item-whatsapp_api", "menu-item-commission", "menu-item-contracts",
        "menu-item-finance", "menu-item-mfa", "menu-item-loyalty", "menu-item-APP Integration",
        "menu-item-Developer", "menu-item-leads", "menu-item-support", "menu-item-affiliate_management",
        "menu-item-appointly", "menu-item-customers", "menu-item-Staff & Role", "menu-item-saas"
    ];

    // Function to close all submenus
    function closeSubmenus() {
        document.querySelectorAll(".nav-second-level").forEach(submenu => {
            submenu.classList.remove("show");
            submenu.style.display = "none";
            submenu.parentElement.classList.remove("active");
        });
    }

    // Restore submenu state after redirection
    if (sessionStorage.getItem("submenuOpen")) {
        let openMenuClass = sessionStorage.getItem("submenuOpen");
        let openMenuItem = document.querySelector(`.${openMenuClass} .nav-second-level`);

        if (openMenuItem) {
            openMenuItem.classList.add("show");
            openMenuItem.style.display = "block";
            document.querySelector(`.${openMenuClass}`).classList.add("active");
        }
    }

    // Close submenu on redirection
    document.querySelectorAll(".nav-second-level a").forEach(link => {
        link.addEventListener("click", function () {
            console.log("Submenu link clicked:", this.href);
            sessionStorage.setItem("submenuOpen", this.closest("li").classList[0]); // Store menu class
        });
    });

    // Submenu Toggle (On Click)
     document.querySelectorAll(".nav > li > a").forEach(menuItem => {
        menuItem.addEventListener("click", function (event) {
            let submenu = this.nextElementSibling;

            if (submenu && submenu.classList.contains("nav-second-level")) {
                event.preventDefault(); // Prevent default navigation for parent link

                let isOpen = submenu.classList.contains("show");

                // Pehle sabhi submenus band karte hain
                document.querySelectorAll(".nav-second-level").forEach(sub => {
                    sub.classList.remove("show");
                    sub.classList.add("collapse");
                    sub.style.display = "none";
                    sub.setAttribute("aria-expanded", "false");
                });

                document.querySelectorAll(".nav > li > a").forEach(item => {
                    item.classList.remove("active");
                });

                // Ab sirf clicked wala submenu open hoga
                if (!isOpen) {
                    submenu.classList.add("show");
                    submenu.classList.remove("collapse");
                    submenu.style.display = "block";
                    submenu.setAttribute("aria-expanded", "true");
                    this.classList.add("active");
                }
            }
        });
    });
});
</script>





</head>

<body <?php echo admin_body_class(isset($bodyclass) ? $bodyclass : ''); ?>>
    <?php hooks()->do_action('after_body_start'); ?>