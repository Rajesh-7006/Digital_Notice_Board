<style>
:root{--primary:#2563eb;--primary-d:#1d4ed8;--bg:#f1f5f9;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--green:#16a34a;--red:#dc2626;--amber:#d97706;--side:#0f172a;}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Roboto,Arial,sans-serif;background:var(--bg);color:var(--text);font-size:15px;line-height:1.5}
a{color:var(--primary);text-decoration:none}
a:hover{text-decoration:underline}

/* Top bar */
.topbar{position:fixed;top:0;left:0;right:0;height:60px;background:#fff;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 24px;z-index:20}
.brand{font-size:18px;font-weight:700;color:var(--text)}
.brand:hover{text-decoration:none}
.top-right{display:flex;align-items:center;gap:20px}
.bell{position:relative;font-size:20px;color:var(--text)}
.bell .dot{position:absolute;top:-6px;right:-10px;background:var(--red);color:#fff;font-size:11px;font-weight:700;border-radius:10px;padding:0 6px}
.user{display:flex;align-items:center;gap:8px;font-weight:600}
.avatar{width:34px;height:34px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}

/* Layout */
.sidebar{position:fixed;top:60px;left:0;bottom:0;width:230px;background:var(--side);padding:20px 12px;display:flex;flex-direction:column;gap:4px}
.sidebar a{color:#cbd5e1;padding:11px 14px;border-radius:8px;font-weight:500;display:block}
.sidebar a:hover{background:#1e293b;color:#fff;text-decoration:none}
.sidebar a.active{background:var(--primary);color:#fff}
.sidebar .spacer{flex:1}
.sidebar a.logout{color:#fca5a5}
.content{margin-left:230px;padding:84px 28px 40px}
.page-head{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:22px}
h1{font-size:24px}
h2{font-size:18px;margin-bottom:14px}
.muted{color:var(--muted)}

/* Cards & stats */
.card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:22px;box-shadow:0 1px 2px rgba(15,23,42,.04);margin-bottom:22px}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:18px;margin-bottom:24px}
.stat{background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;text-align:center;border-top:4px solid var(--primary)}
.stat .num{font-size:34px;font-weight:700}
.stat .lbl{color:var(--muted);font-size:14px}
.stat.green{border-top-color:var(--green)}.stat.red{border-top-color:var(--red)}.stat.amber{border-top-color:var(--amber)}

/* Tables */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{text-align:left;font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);padding:10px 12px;border-bottom:2px solid var(--border)}
td{padding:13px 12px;border-bottom:1px solid var(--border);vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#f8fafc}
.actions{display:flex;gap:8px;flex-wrap:wrap}
.actions form{display:inline}
.empty{text-align:center;color:var(--muted);padding:30px}

/* Badges */
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:.03em}
.badge.urgent{background:#fee2e2;color:#b91c1c}
.badge.important{background:#fef3c7;color:#b45309}
.badge.normal{background:#dbeafe;color:#1d4ed8}
.badge.st-published{background:#dcfce7;color:#15803d}
.badge.st-expired{background:#e2e8f0;color:#475569}
.badge.st-draft{background:#f3e8ff;color:#7e22ce}
.badge.st-pending{background:#fef3c7;color:#b45309}
.badge.st-approved{background:#dcfce7;color:#15803d}

/* Buttons */
.btn{display:inline-block;border:none;cursor:pointer;background:var(--primary);color:#fff;padding:9px 18px;border-radius:8px;font-size:14px;font-weight:600;font-family:inherit}
.btn:hover{background:var(--primary-d);text-decoration:none;color:#fff}
.btn.sm{padding:5px 12px;font-size:13px}
.btn.green{background:var(--green)}.btn.red{background:var(--red)}
.btn.gray{background:#fff;color:var(--text);border:1px solid var(--border)}
.btn.gray:hover{background:#f1f5f9;color:var(--text)}

/* Forms */
.form-group{margin-bottom:18px}
label{display:block;font-weight:600;margin-bottom:6px;font-size:14px}
input[type=text],input[type=email],input[type=password],input[type=date],select,textarea{width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:15px;font-family:inherit;background:#fff}
input:focus,select:focus,textarea:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,.15)}
textarea{min-height:180px;resize:vertical}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.filters{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px}
.filters input,.filters select{width:auto;min-width:180px}

/* Alerts */
.alert{padding:12px 16px;border-radius:8px;margin-bottom:20px;font-weight:500}
.alert.success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
.alert.error{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}

/* Login & public pages */
.login-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.login-card{width:100%;max-width:400px;background:#fff;border:1px solid var(--border);border-radius:14px;padding:32px;box-shadow:0 10px 30px rgba(15,23,42,.08)}
.login-card h1{text-align:center;margin-bottom:6px}
.login-card p.muted{text-align:center;margin-bottom:22px}
.public-wrap{max-width:900px;margin:0 auto;padding:90px 20px 40px}
.notice-card{border-left:5px solid var(--primary)}
.notice-card.urgent{border-left-color:var(--red)}.notice-card.important{border-left-color:var(--amber)}
.notice-card h2{margin:8px 0}
.comment{background:#f8fafc;border-radius:8px;padding:10px 14px;margin-top:8px;font-size:14px}

@media(max-width:800px){
  .sidebar{position:static;width:100%;flex-direction:row;flex-wrap:wrap;margin-top:60px;padding:10px}
  .sidebar .spacer{display:none}
  .content{margin-left:0;padding:20px 16px}
  .form-row{grid-template-columns:1fr}
}
</style>
