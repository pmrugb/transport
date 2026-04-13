(function () {
  const target = document.querySelector("[data-shared-header]");

  if (!target) {
    return;
  }

  const currentPage = window.location.pathname.split("/").pop() || "dashboard.html";
  const pageLabels = {
    "dashboard.html": "Dashboard Overview",
    "transporters.html": "Operator Management",
    "routes.html": "Route Management"
  };

  target.innerHTML = `
    <header class="app-topbar">
      <div class="container-fluid app-topbar-inner">
        <button class="btn app-topbar-toggle" type="button" data-app-sidebar-toggle aria-label="Toggle navigation">
          <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="app-topbar-actions">
          <span class="app-topbar-badge d-none d-md-inline-flex">${pageLabels[currentPage] || "PMRU GB Portal"}</span>
          <a class="app-topbar-emblem" href="dashboard.html" aria-label="Back to dashboard">
            <img alt="Portal emblem" src="image/emblem.svg"/>
          </a>
        </div>
      </div>
    </header>
  `;
})();
