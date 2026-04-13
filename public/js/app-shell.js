(function () {
  const sidebarTarget = document.querySelector("[data-shared-sidebar]");
  const mobileTarget = document.querySelector("[data-mobile-nav]");
  const body = document.body;

  const currentPage = window.location.pathname.split("/").pop() || "dashboard.html";
  let activeKey = currentPage.replace(".html", "");

  if (currentPage === "transporters.html") {
    activeKey = "companies";
  }

  const navItems = [
    { key: "dashboard", href: "dashboard.html", icon: "dashboard", label: "Dashboard" },
    { key: "companies", href: "transporters.html", icon: "groups", label: "Companies / Private" },
    { key: "routes", href: "routes.html", icon: "alt_route", label: "Routes" }
  ];

  function buildNavMarkup() {
    return navItems
      .map(
        (item) => `
          <li class="nav-item">
            <a class="nav-link${item.key === activeKey ? " active" : ""}" ${item.key === activeKey ? 'aria-current="page"' : ""} href="${item.href}">
              <span class="nav-link-icon">
                <span class="material-symbols-outlined">${item.icon}</span>
              </span>
              <span class="nav-link-text">${item.label}</span>
              <span class="material-symbols-outlined nav-link-arrow">chevron_right</span>
            </a>
          </li>
        `
      )
      .join("");
  }

  const sidebarMarkup = `
    <div class="app-sidebar-shell">
      <a class="app-sidebar-brand" href="dashboard.html">
        <span class="app-brand-mark">
          <span class="material-symbols-outlined">directions_bus</span>
        </span>
        <span class="app-brand-copy">
          <span class="app-sidebar-title">Free Public</span>
          <span class="app-sidebar-title">Transport System</span>
          <span class="app-sidebar-subtitle">PMRU GB Operations</span>
        </span>
      </a>
      <ul class="nav flex-column app-sidebar-nav">
        ${buildNavMarkup()}
      </ul>
      <div class="app-sidebar-utility">
        <a class="app-sidebar-logout" href="login.html">
          <span class="nav-link-icon">
            <span class="material-symbols-outlined">logout</span>
          </span>
          <span class="nav-link-text">Logout</span>
        </a>
      </div>
      <div class="app-sidebar-legal">
        ©2026 All Rights Reserved.<br/>Powered by <span>PMRU GB</span>
      </div>
    </div>
  `;

  if (sidebarTarget) {
    sidebarTarget.innerHTML = sidebarMarkup;
  }

  if (mobileTarget) {
    mobileTarget.innerHTML = `
      <div class="offcanvas offcanvas-start app-mobile-menu" tabindex="-1" id="appMobileMenu" aria-labelledby="appMobileMenuLabel">
        <div class="offcanvas-header">
          <a class="app-sidebar-brand mb-0" href="dashboard.html">
            <span class="app-brand-mark">
              <span class="material-symbols-outlined">directions_bus</span>
            </span>
            <span class="app-brand-copy">
              <span class="app-sidebar-title">Free Public</span>
              <span class="app-sidebar-title">Transport System</span>
              <span class="app-sidebar-subtitle">PMRU GB Operations</span>
            </span>
          </a>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="nav flex-column app-sidebar-nav">
            ${buildNavMarkup()}
          </ul>
          <div class="app-sidebar-utility">
            <a class="app-sidebar-logout" href="login.html">
              <span class="nav-link-icon">
                <span class="material-symbols-outlined">logout</span>
              </span>
              <span class="nav-link-text">Logout</span>
            </a>
          </div>
          <div class="app-sidebar-legal">
            ©2026 All Rights Reserved.<br/>Powered by <span>PMRU GB</span>
          </div>
        </div>
      </div>
    `;
  }

  document.addEventListener("click", function (event) {
    const toggleButton = event.target.closest("[data-app-sidebar-toggle]");

    if (!toggleButton) {
      return;
    }

    event.preventDefault();

    if (window.innerWidth < 992) {
      const mobileMenu = document.getElementById("appMobileMenu");

      if (mobileMenu && window.bootstrap && window.bootstrap.Offcanvas) {
        window.bootstrap.Offcanvas.getOrCreateInstance(mobileMenu).toggle();
      }

      return;
    }

    body.classList.toggle("sidebar-collapsed");
  });

  if (mobileTarget) {
    mobileTarget.addEventListener("click", function (event) {
      const navLink = event.target.closest("a[href]");
      const mobileMenu = document.getElementById("appMobileMenu");

      if (!navLink || !mobileMenu || !window.bootstrap || !window.bootstrap.Offcanvas) {
        return;
      }

      window.bootstrap.Offcanvas.getOrCreateInstance(mobileMenu).hide();
    });
  }
})();
