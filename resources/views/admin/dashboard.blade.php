<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LaptopHub — Admin Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --ink:       #0f0f0f;
      --paper:     #f7f4ef;
      --cream:     #edeae3;
      --accent:    #c84b2f;
      --accent2:   #2f6bc8;
      --muted:     #7a7670;
      --sidebar-w: 260px;
      --border:    #d8d4cc;
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--paper);
      color: var(--ink);
      min-height: 100vh;
    }

    /* ── SIDEBAR ────────────────────────────── */
    .sidebar {
      width: var(--sidebar-w);
      min-height: 100vh;
      background: var(--ink);
      color: #fff;
      position: fixed;
      top: 0; left: 0;
      display: flex;
      flex-direction: column;
      border-right: 3px solid var(--accent);
      z-index: 100;
    }

    .sidebar-brand {
      padding: 2rem 1.75rem 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,.1);
    }
    .sidebar-brand .wordmark {
      font-family: 'DM Serif Display', serif;
      font-size: 1.55rem;
      letter-spacing: -.5px;
      line-height: 1;
      color: #fff;
    }
    .sidebar-brand .badge-admin {
      font-size: .6rem;
      background: var(--accent);
      color: #fff;
      padding: .2em .55em;
      border-radius: 3px;
      letter-spacing: .08em;
      text-transform: uppercase;
      vertical-align: middle;
      margin-left: .4rem;
    }

    .sidebar-nav { flex: 1; padding: 1.25rem 0; }

    .nav-section-label {
      font-size: .65rem;
      letter-spacing: .15em;
      text-transform: uppercase;
      color: rgba(255,255,255,.35);
      padding: 1rem 1.75rem .4rem;
    }

    .sidebar-nav .nav-link {
      display: flex;
      align-items: center;
      gap: .75rem;
      color: rgba(255,255,255,.7);
      padding: .6rem 1.75rem;
      font-size: .875rem;
      font-weight: 400;
      border-left: 3px solid transparent;
      transition: color .15s, border-color .15s, background .15s;
      text-decoration: none;
    }
    .sidebar-nav .nav-link:hover {
      color: #fff;
      background: rgba(255,255,255,.06);
    }
    .sidebar-nav .nav-link.active {
      color: #fff;
      border-left-color: var(--accent);
      background: rgba(200,75,47,.12);
      font-weight: 500;
    }
    .sidebar-nav .nav-link i { font-size: 1rem; width: 1.25rem; text-align: center; }

    .sidebar-footer {
      padding: 1.25rem 1.75rem;
      border-top: 1px solid rgba(255,255,255,.1);
      font-size: .8rem;
      color: rgba(255,255,255,.4);
    }
    .sidebar-footer .avatar {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      font-size: .75rem; font-weight: 600; color: #fff;
      flex-shrink: 0;
    }

    /* ── MAIN ───────────────────────────────── */
    .main {
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── TOPBAR ─────────────────────────────── */
    .topbar {
      background: var(--paper);
      border-bottom: 1px solid var(--border);
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .topbar h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.6rem;
      margin: 0;
      letter-spacing: -.5px;
    }
    .topbar .sub {
      font-size: .8rem;
      color: var(--muted);
      margin: 0;
    }
    .topbar-actions .btn {
      font-size: .8rem;
    }

    /* ── CONTENT ────────────────────────────── */
    .content { padding: 2rem; flex: 1; }

    /* ── STAT CARDS ─────────────────────────── */
    .stat-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 1.5rem;
      position: relative;
      overflow: hidden;
      transition: box-shadow .15s;
    }
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
    }
    .stat-card.red::before   { background: var(--accent); }
    .stat-card.blue::before  { background: var(--accent2); }
    .stat-card.gold::before  { background: #c89a2f; }
    .stat-card.green::before { background: #2f9c5a; }

    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); }

    .stat-card .label {
      font-size: .7rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: .5rem;
    }
    .stat-card .value {
      font-family: 'DM Serif Display', serif;
      font-size: 2.2rem;
      line-height: 1;
      color: var(--ink);
    }
    .stat-card .change {
      font-size: .75rem;
      margin-top: .4rem;
    }
    .stat-card .icon {
      position: absolute;
      top: 1.25rem; right: 1.25rem;
      font-size: 1.75rem;
      opacity: .12;
      color: var(--ink);
    }

    /* ── SECTION TITLE ──────────────────────── */
    .section-title {
      font-family: 'DM Serif Display', serif;
      font-size: 1.15rem;
      letter-spacing: -.3px;
      margin-bottom: 1rem;
    }

    /* ── TABLE ──────────────────────────────── */
    .table-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      overflow: hidden;
    }
    .table-card .card-header {
      background: #fff;
      border-bottom: 1px solid var(--border);
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .table-card .card-header h5 {
      font-family: 'DM Serif Display', serif;
      font-size: 1rem;
      margin: 0;
    }
    .table > thead > tr > th {
      font-size: .68rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--muted);
      font-weight: 500;
      background: var(--cream);
      border-bottom: 1px solid var(--border) !important;
      padding: .75rem 1rem;
    }
    .table > tbody > tr > td {
      font-size: .83rem;
      padding: .75rem 1rem;
      vertical-align: middle;
      border-color: var(--cream);
    }
    .table > tbody > tr:last-child > td { border-bottom: none; }
    .table > tbody > tr:hover > td { background: var(--cream); }

    /* ── BADGES ─────────────────────────────── */
    .status-badge {
      display: inline-block;
      font-size: .65rem;
      font-weight: 600;
      letter-spacing: .07em;
      text-transform: uppercase;
      padding: .25em .65em;
      border-radius: 3px;
    }
    .badge-pending    { background: #fff3cd; color: #856404; }
    .badge-processing { background: #cfe2ff; color: #084298; }
    .badge-shipped    { background: #e2e3e5; color: #383d41; }
    .badge-delivered  { background: #d1e7dd; color: #0a3622; }
    .badge-cancelled  { background: #f8d7da; color: #842029; }

    /* ── MINI CHART (pure CSS bar) ──────────── */
    .mini-bars {
      display: flex;
      align-items: flex-end;
      gap: 4px;
      height: 50px;
    }
    .mini-bars .bar {
      flex: 1;
      background: var(--accent);
      opacity: .2;
      border-radius: 2px 2px 0 0;
      transition: opacity .15s;
    }
    .mini-bars .bar:hover { opacity: .6; }
    .mini-bars .bar.hi    { opacity: .75; }

    /* ── LOW STOCK ──────────────────────────── */
    .stock-bar-wrap { background: var(--cream); border-radius: 3px; height: 6px; }
    .stock-bar      { height: 6px; border-radius: 3px; }
    .stock-ok   { background: #2f9c5a; }
    .stock-low  { background: var(--accent); }
    .stock-none { background: #842029; }

    /* ── ACTIVITY FEED ──────────────────────── */
    .activity-item {
      display: flex;
      gap: 1rem;
      padding: .85rem 1.25rem;
      border-bottom: 1px solid var(--cream);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      margin-top: .35rem;
      flex-shrink: 0;
    }
    .activity-item .time {
      font-size: .7rem;
      color: var(--muted);
      white-space: nowrap;
      margin-top: .1rem;
    }
    .activity-item .text { font-size: .82rem; line-height: 1.4; }

    /* ── QUICK ACTIONS ──────────────────────── */
    .quick-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .5rem;
      padding: 1.25rem .5rem;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      text-decoration: none;
      color: var(--ink);
      font-size: .75rem;
      font-weight: 500;
      text-align: center;
      transition: box-shadow .15s, border-color .15s;
    }
    .quick-btn:hover {
      box-shadow: 0 2px 10px rgba(0,0,0,.08);
      border-color: var(--accent);
      color: var(--accent);
    }
    .quick-btn i { font-size: 1.4rem; }

    /* ── REVIEW STARS ───────────────────────── */
    .stars { color: #c89a2f; font-size: .85rem; letter-spacing: -1px; }

    /* ── SCROLLBAR ──────────────────────────── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
  </style>
</head>
<body>

<!-- ═══════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════ -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="wordmark">LaptopHub <span class="badge-admin">Admin</span></div>
    <div class="mt-1" style="font-size:.75rem;color:rgba(255,255,255,.4)">Management Console</div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Overview</div>
    <a href="#" class="nav-link active"><i class="bi bi-grid-1x2"></i> Dashboard</a>

    <div class="nav-section-label">Catalog</div>
    <a href="#" class="nav-link"><i class="bi bi-laptop"></i> Products</a>
    <a href="#" class="nav-link"><i class="bi bi-tags"></i> Categories</a>
    <a href="#" class="nav-link"><i class="bi bi-award"></i> Brands</a>

    <div class="nav-section-label">Commerce</div>
    <a href="#" class="nav-link"><i class="bi bi-bag-check"></i> Orders</a>
    <a href="#" class="nav-link"><i class="bi bi-cart3"></i> Carts</a>
    <a href="#" class="nav-link"><i class="bi bi-star-half"></i> Reviews</a>

    <div class="nav-section-label">Operations</div>
    <a href="#" class="nav-link"><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="#" class="nav-link"><i class="bi bi-truck"></i> Suppliers</a>
    <a href="#" class="nav-link"><i class="bi bi-arrow-repeat"></i> Restock Log</a>

    <div class="nav-section-label">Users</div>
    <a href="#" class="nav-link"><i class="bi bi-people"></i> All Users</a>
    <a href="#" class="nav-link"><i class="bi bi-shield-lock"></i> Roles</a>

    <div class="nav-section-label">System</div>
    <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
  </nav>

  <div class="sidebar-footer d-flex align-items-center gap-2">
    <div class="avatar">AD</div>
    <div>
      <div style="color:#fff;font-weight:500;font-size:.8rem">Admin User</div>
      <div>admin@laptophub.ph</div>
    </div>
  </div>
</aside>

<!-- ═══════════════════════════════════════════
     MAIN
═══════════════════════════════════════════ -->
<div class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <div>
      <h1>Dashboard</h1>
      <p class="sub">Thursday, 26 February 2026 &nbsp;·&nbsp; Welcome back, Admin</p>
    </div>
    <div class="topbar-actions d-flex gap-2">
      <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download me-1"></i>Export</a>
      <a href="#" class="btn btn-sm text-white" style="background:var(--accent)"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
      <form action="{{ route('logout') }}" method="post" class="m-0">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm">
          <i class="bi bi-box-arrow-right me-1"></i>Logout
        </button>
      </form>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="content">

    <!-- ── STAT CARDS ── -->
    <div class="row g-3 mb-4">

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card red">
          <i class="bi bi-bag icon"></i>
          <div class="label">Total Orders</div>
          <div class="value">1,284</div>
          <div class="change text-success"><i class="bi bi-arrow-up-short"></i> 12.4% vs last month</div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card blue">
          <i class="bi bi-currency-dollar icon"></i>
          <div class="label">Revenue (MTD)</div>
          <div class="value">₱2.38M</div>
          <div class="change text-success"><i class="bi bi-arrow-up-short"></i> 8.1% vs last month</div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card gold">
          <i class="bi bi-people icon"></i>
          <div class="label">Active Users</div>
          <div class="value">4,920</div>
          <div class="change text-success"><i class="bi bi-arrow-up-short"></i> 3.2% this week</div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card green">
          <i class="bi bi-box-seam icon"></i>
          <div class="label">Products Active</div>
          <div class="value">348</div>
          <div class="change" style="color:var(--accent)"><i class="bi bi-exclamation-circle me-1"></i>9 low stock</div>
        </div>
      </div>

    </div>

    <!-- ── ROW 2: ORDERS TABLE + QUICK ACTIONS ── -->
    <div class="row g-3 mb-4">

      <!-- Recent Orders -->
      <div class="col-12 col-xl-8">
        <div class="table-card">
          <div class="card-header">
            <h5>Recent Orders</h5>
            <a href="#" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem">View All</a>
          </div>
          <div class="table-responsive">
            <table class="table mb-0">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Customer</th>
                  <th>Items</th>
                  <th>Total</th>
                  <th>Payment</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>#ORD-1284</strong></td>
                  <td>Maria Santos</td>
                  <td>2</td>
                  <td>₱58,900</td>
                  <td><span class="badge bg-light text-dark border">Online</span></td>
                  <td><span class="status-badge badge-delivered">Delivered</span></td>
                  <td><a href="#" style="font-size:.75rem;color:var(--accent2)">View</a></td>
                </tr>
                <tr>
                  <td><strong>#ORD-1283</strong></td>
                  <td>Juan dela Cruz</td>
                  <td>1</td>
                  <td>₱42,500</td>
                  <td><span class="badge bg-light text-dark border">COD</span></td>
                  <td><span class="status-badge badge-shipped">Shipped</span></td>
                  <td><a href="#" style="font-size:.75rem;color:var(--accent2)">View</a></td>
                </tr>
                <tr>
                  <td><strong>#ORD-1282</strong></td>
                  <td>Lena Reyes</td>
                  <td>3</td>
                  <td>₱128,700</td>
                  <td><span class="badge bg-light text-dark border">Online</span></td>
                  <td><span class="status-badge badge-processing">Processing</span></td>
                  <td><a href="#" style="font-size:.75rem;color:var(--accent2)">View</a></td>
                </tr>
                <tr>
                  <td><strong>#ORD-1281</strong></td>
                  <td>Carlo Mendoza</td>
                  <td>1</td>
                  <td>₱15,200</td>
                  <td><span class="badge bg-light text-dark border">COD</span></td>
                  <td><span class="status-badge badge-pending">Pending</span></td>
                  <td><a href="#" style="font-size:.75rem;color:var(--accent2)">View</a></td>
                </tr>
                <tr>
                  <td><strong>#ORD-1280</strong></td>
                  <td>Ana Torres</td>
                  <td>2</td>
                  <td>₱73,400</td>
                  <td><span class="badge bg-light text-dark border">Online</span></td>
                  <td><span class="status-badge badge-cancelled">Cancelled</span></td>
                  <td><a href="#" style="font-size:.75rem;color:var(--accent2)">View</a></td>
                </tr>
                <tr>
                  <td><strong>#ORD-1279</strong></td>
                  <td>Rex Castillo</td>
                  <td>1</td>
                  <td>₱34,800</td>
                  <td><span class="badge bg-light text-dark border">Online</span></td>
                  <td><span class="status-badge badge-delivered">Delivered</span></td>
                  <td><a href="#" style="font-size:.75rem;color:var(--accent2)">View</a></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="col-12 col-xl-4">
        <div class="section-title">Quick Actions</div>
        <div class="row g-2 mb-3">
          <div class="col-6">
            <a href="#" class="quick-btn">
              <i class="bi bi-laptop"></i>
              Add Product
            </a>
          </div>
          <div class="col-6">
            <a href="#" class="quick-btn">
              <i class="bi bi-person-plus"></i>
              Add User
            </a>
          </div>
          <div class="col-6">
            <a href="#" class="quick-btn">
              <i class="bi bi-arrow-repeat"></i>
              Restock
            </a>
          </div>
          <div class="col-6">
            <a href="#" class="quick-btn">
              <i class="bi bi-truck"></i>
              Add Supplier
            </a>
          </div>
          <div class="col-6">
            <a href="#" class="quick-btn">
              <i class="bi bi-tags"></i>
              New Category
            </a>
          </div>
          <div class="col-6">
            <a href="#" class="quick-btn">
              <i class="bi bi-bar-chart-line"></i>
              Sales Report
            </a>
          </div>
        </div>

        <!-- Order Status Breakdown -->
        <div class="table-card">
          <div class="card-header">
            <h5>Order Status Breakdown</h5>
          </div>
          <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <small class="text-muted">Delivered</small>
              <small><strong>648</strong></small>
            </div>
            <div class="progress mb-3" style="height:5px;border-radius:2px">
              <div class="progress-bar" style="width:50%;background:#2f9c5a"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <small class="text-muted">Shipped</small>
              <small><strong>257</strong></small>
            </div>
            <div class="progress mb-3" style="height:5px;border-radius:2px">
              <div class="progress-bar" style="width:20%;background:var(--accent2)"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <small class="text-muted">Processing</small>
              <small><strong>189</strong></small>
            </div>
            <div class="progress mb-3" style="height:5px;border-radius:2px">
              <div class="progress-bar" style="width:15%;background:#c89a2f"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <small class="text-muted">Pending</small>
              <small><strong>128</strong></small>
            </div>
            <div class="progress mb-3" style="height:5px;border-radius:2px">
              <div class="progress-bar" style="width:10%;background:#c89a2f;opacity:.5"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <small class="text-muted">Cancelled</small>
              <small><strong>62</strong></small>
            </div>
            <div class="progress" style="height:5px;border-radius:2px">
              <div class="progress-bar" style="width:5%;background:var(--accent)"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── ROW 3: LOW STOCK + ACTIVITY + TOP PRODUCTS ── -->
    <div class="row g-3 mb-4">

      <!-- Low Stock Alert -->
      <div class="col-12 col-lg-4">
        <div class="table-card h-100">
          <div class="card-header">
            <h5><i class="bi bi-exclamation-triangle me-1" style="color:var(--accent)"></i>Low Stock Alert</h5>
            <a href="#" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem">Restock All</a>
          </div>
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Level</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">Lenovo IdeaPad 3</div>
                  <div style="font-size:.7rem;color:var(--muted)">Laptops</div>
                </td>
                <td><strong>2</strong></td>
                <td>
                  <div class="stock-bar-wrap"><div class="stock-bar stock-low" style="width:10%"></div></div>
                </td>
              </tr>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">Crucial 16GB DDR5</div>
                  <div style="font-size:.7rem;color:var(--muted)">RAM</div>
                </td>
                <td><strong>3</strong></td>
                <td>
                  <div class="stock-bar-wrap"><div class="stock-bar stock-low" style="width:18%"></div></div>
                </td>
              </tr>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">Samsung 980 Pro 1TB</div>
                  <div style="font-size:.7rem;color:var(--muted)">Storage</div>
                </td>
                <td><strong>0</strong></td>
                <td>
                  <div class="stock-bar-wrap"><div class="stock-bar stock-none" style="width:2%"></div></div>
                </td>
              </tr>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">Asus TUF A15</div>
                  <div style="font-size:.7rem;color:var(--muted)">Gaming</div>
                </td>
                <td><strong>4</strong></td>
                <td>
                  <div class="stock-bar-wrap"><div class="stock-bar stock-low" style="width:22%"></div></div>
                </td>
              </tr>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">Razer Blade 15</div>
                  <div style="font-size:.7rem;color:var(--muted)">Gaming</div>
                </td>
                <td><strong>1</strong></td>
                <td>
                  <div class="stock-bar-wrap"><div class="stock-bar stock-none" style="width:5%"></div></div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="col-12 col-lg-4">
        <div class="table-card h-100">
          <div class="card-header">
            <h5>Recent Activity</h5>
          </div>
          <div>
            <div class="activity-item">
              <div class="activity-dot" style="background:#2f9c5a"></div>
              <div class="flex-grow-1">
                <div class="text">Order <strong>#ORD-1284</strong> marked as <em>Delivered</em></div>
                <div class="time">2 min ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot" style="background:var(--accent2)"></div>
              <div class="flex-grow-1">
                <div class="text">New user <strong>Rex Castillo</strong> registered</div>
                <div class="time">14 min ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot" style="background:#c89a2f"></div>
              <div class="flex-grow-1">
                <div class="text">Restocked <strong>Samsung 980 Pro</strong> — +50 units by InventoryMgr</div>
                <div class="time">1 hr ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot" style="background:var(--accent)"></div>
              <div class="flex-grow-1">
                <div class="text">Order <strong>#ORD-1280</strong> cancelled by customer</div>
                <div class="time">2 hr ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot" style="background:#2f9c5a"></div>
              <div class="flex-grow-1">
                <div class="text">New review posted for <strong>Asus VivoBook 15</strong> — <span class="stars">★★★★★</span></div>
                <div class="time">3 hr ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot" style="background:var(--accent2)"></div>
              <div class="flex-grow-1">
                <div class="text">Product <strong>Lenovo ThinkPad X1</strong> added to catalog</div>
                <div class="time">5 hr ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-dot" style="background:#c89a2f"></div>
              <div class="flex-grow-1">
                <div class="text">Supplier <strong>TechSource PH</strong> profile updated</div>
                <div class="time">Yesterday</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Reviewed Products -->
      <div class="col-12 col-lg-4">
        <div class="table-card h-100">
          <div class="card-header">
            <h5>Top Reviewed Products</h5>
          </div>
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Product</th>
                <th>Avg</th>
                <th>Reviews</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">MacBook Air M3</div>
                  <div style="font-size:.7rem;color:var(--muted)">Apple</div>
                </td>
                <td><span class="stars">★</span> <strong>4.9</strong></td>
                <td>134</td>
              </tr>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">Dell XPS 15</div>
                  <div style="font-size:.7rem;color:var(--muted)">Dell</div>
                </td>
                <td><span class="stars">★</span> <strong>4.7</strong></td>
                <td>98</td>
              </tr>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">Asus VivoBook 15</div>
                  <div style="font-size:.7rem;color:var(--muted)">Asus</div>
                </td>
                <td><span class="stars">★</span> <strong>4.5</strong></td>
                <td>87</td>
              </tr>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">Lenovo ThinkPad E15</div>
                  <div style="font-size:.7rem;color:var(--muted)">Lenovo</div>
                </td>
                <td><span class="stars">★</span> <strong>4.4</strong></td>
                <td>72</td>
              </tr>
              <tr>
                <td>
                  <div style="font-size:.8rem;font-weight:500">HP Spectre x360</div>
                  <div style="font-size:.7rem;color:var(--muted)">HP</div>
                </td>
                <td><span class="stars">★</span> <strong>4.3</strong></td>
                <td>61</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ── ROW 4: USERS + SUPPLIERS ── -->
    <div class="row g-3">

      <!-- Recent Users -->
      <div class="col-12 col-lg-6">
        <div class="table-card">
          <div class="card-header">
            <h5>Recent Users</h5>
            <a href="#" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem">Manage Users</a>
          </div>
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Rex Castillo</td>
                <td style="font-size:.75rem;color:var(--muted)">rex@mail.com</td>
                <td><span class="badge bg-light text-dark border" style="font-size:.65rem">Customer</span></td>
                <td><span class="badge" style="background:#d1e7dd;color:#0a3622;font-size:.65rem">Active</span></td>
              </tr>
              <tr>
                <td>Maria Santos</td>
                <td style="font-size:.75rem;color:var(--muted)">maria@mail.com</td>
                <td><span class="badge bg-light text-dark border" style="font-size:.65rem">Customer</span></td>
                <td><span class="badge" style="background:#d1e7dd;color:#0a3622;font-size:.65rem">Active</span></td>
              </tr>
              <tr>
                <td>Jess Lim</td>
                <td style="font-size:.75rem;color:var(--muted)">jess@staff.com</td>
                <td><span class="badge" style="background:#cfe2ff;color:#084298;font-size:.65rem">InventoryMgr</span></td>
                <td><span class="badge" style="background:#d1e7dd;color:#0a3622;font-size:.65rem">Active</span></td>
              </tr>
              <tr>
                <td>Anon User #48</td>
                <td style="font-size:.75rem;color:var(--muted)">anon48@mail.com</td>
                <td><span class="badge bg-light text-dark border" style="font-size:.65rem">Customer</span></td>
                <td><span class="badge" style="background:#f8d7da;color:#842029;font-size:.65rem">Inactive</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Active Suppliers -->
      <div class="col-12 col-lg-6">
        <div class="table-card">
          <div class="card-header">
            <h5>Active Suppliers</h5>
            <a href="#" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem">All Suppliers</a>
          </div>
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Supplier</th>
                <th>Contact</th>
                <th>Products</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong style="font-size:.83rem">TechSource PH</strong></td>
                <td style="font-size:.75rem;color:var(--muted)">Marco Tan</td>
                <td>48</td>
                <td><span class="badge" style="background:#d1e7dd;color:#0a3622;font-size:.65rem">Active</span></td>
              </tr>
              <tr>
                <td><strong style="font-size:.83rem">GadgetWholesale Inc.</strong></td>
                <td style="font-size:.75rem;color:var(--muted)">Ella Cruz</td>
                <td>31</td>
                <td><span class="badge" style="background:#d1e7dd;color:#0a3622;font-size:.65rem">Active</span></td>
              </tr>
              <tr>
                <td><strong style="font-size:.83rem">CompTech Distributors</strong></td>
                <td style="font-size:.75rem;color:var(--muted)">Rene Go</td>
                <td>22</td>
                <td><span class="badge" style="background:#d1e7dd;color:#0a3622;font-size:.65rem">Active</span></td>
              </tr>
              <tr>
                <td><strong style="font-size:.83rem">Macro Components</strong></td>
                <td style="font-size:.75rem;color:var(--muted)">Lydia Ong</td>
                <td>15</td>
                <td><span class="badge" style="background:#fff3cd;color:#856404;font-size:.65rem">Pending</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div><!-- /row -->

    <div class="mt-4 text-center" style="font-size:.72rem;color:var(--muted);padding-bottom:1rem">
      LaptopHub Admin Console &nbsp;·&nbsp; v1.0.0 &nbsp;·&nbsp; &copy; 2026 LaptopHub
    </div>

  </div><!-- /content -->
</div><!-- /main -->

</body>
</html>