// Prevent filter bar injection on non-dashboard pages
if (!window.location.pathname.includes('index.php')) {
  const existingFilterBar = document.querySelector('.filter-bar');
  if (existingFilterBar) existingFilterBar.remove();
}

document.addEventListener("DOMContentLoaded", () => {
  const tbody = document.getElementById("batchData");
  const userRole = window.userRole || "Guest";
  let allBatches = [];

  if (window.location.pathname.includes('index.php')) {
  const filterBar = document.createElement("div");
  filterBar.className = "filter-bar";
  filterBar.innerHTML = `
    <input type="text" id="searchInput" placeholder="Search by product name...">
    <select id="expiryFilterSelect">
      <option value="">All Expiry Status</option>
      <option value="Valid">Valid</option>
      <option value="Expiring Soon">Expiring Soon</option>
      <option value="Expired">Expired</option>
    </select>
    <select id="inspectionFilter">
      <option value="">All Inspection</option>
      <option value="Pass">Pass</option>
      <option value="Fail">Fail</option>
      <option value="N/A">N/A</option>
    </select>
    <input type="date" id="expiryDateFilter">
    <button class="btn" id="resetFilters">Reset</button>
  `;
  const content = document.querySelector(".content");
  content.insertBefore(filterBar, content.querySelector("table"));
  }

  fetch("fetch_batches.php").then(r=>r.json()).then(data=>{ allBatches = data; renderTable(allBatches);}).catch(console.error);
  fetch("analytics_data.php").then(r=>r.json()).then(data=>{
    document.getElementById("totalProducts").textContent = `Total Products: ${data.totalProducts}`;
    document.getElementById("nearExpiry").textContent = `Expiring Soon: ${data.nearExpiry}`;
    document.getElementById("failedInspections").textContent = `Failed Inspections: ${data.failedInspections}`;
    const ctx = document.getElementById("inspectionChart");
    new Chart(ctx, {
      type:"doughnut",
      data:{labels:["Pass","Fail"],datasets:[{data:[data.chart.Pass||0,data.chart.Fail||0],backgroundColor:["#4caf50","#f44336"]}]},
      options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:"bottom"}}}
    });
  }).catch(console.error);

  document.getElementById("searchInput").addEventListener("input", filterTable);
  document.getElementById("expiryFilterSelect").addEventListener("change", filterTable);
  document.getElementById("inspectionFilter").addEventListener("change", filterTable);
  document.getElementById("expiryDateFilter").addEventListener("change", filterTable);
  document.getElementById("resetFilters").addEventListener("click", ()=>{ document.getElementById("searchInput").value=""; document.getElementById("expiryFilterSelect").value=""; document.getElementById("inspectionFilter").value=""; document.getElementById("expiryDateFilter").value=""; renderTable(allBatches); });

  function filterTable(){
    const search = document.getElementById("searchInput").value.toLowerCase();
    const expirySel = document.getElementById("expiryFilterSelect").value;
    const inspectionSel = document.getElementById("inspectionFilter").value;
    const expiryDate = document.getElementById("expiryDateFilter").value;
    const filtered = allBatches.filter(b => {
      const matchesSearch = b.product_name.toLowerCase().includes(search);
      const matchesExpiry = expirySel === "" || b.expiry_status === expirySel;
      const matchesInspection = inspectionSel === "" || (b.last_inspection_status || 'N/A') === inspectionSel;
      const matchesExpiryDate = expiryDate === "" || b.expiry_date === expiryDate;
      return matchesSearch && matchesExpiry && matchesInspection && matchesExpiryDate;
    });
    renderTable(filtered);
  }

  function renderTable(batches){
    tbody.innerHTML = "";
    batches.forEach((batch,index)=>{
      const row = document.createElement("tr");
      row.style.opacity = 0;
      const expiryClass = batch.expiry_status.toLowerCase().replace(/\s+/g,'-');
      if (expiryClass === 'valid') row.classList.add('status-valid');
      else if (expiryClass === 'expiring-soon') row.classList.add('status-expiring');
      else if (expiryClass === 'expired') row.classList.add('status-expired');

      let actions = '';
      if (['Admin','Developer'].includes(userRole)){
        actions = `<td><button class="btn" onclick="window.location.href='edit_batch.php?id=${batch.batch_id}'">Edit</button> <button class="btn delete" onclick="confirmDelete('delete_batch.php?id=${batch.batch_id}', 'Delete batch #${batch.batch_id}? This will remove related inspections.')"
>Delete</button></td>`;
      }

      row.innerHTML = `<td>${batch.batch_id}</td><td>${escapeHtml(batch.product_name)}</td><td>${batch.expiry_date}</td><td>${batch.expiry_status}</td><td>${batch.last_inspection_status}</td>${actions}`;
      tbody.appendChild(row);
      setTimeout(()=>row.style.opacity = 1, index*30);
    });
  }

  function escapeHtml(s){ return String(s).replace(/[&<>"'\/]/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;'}[ch])); }
});
// === Delete Confirmation Modal ===
let deleteUrl = '';

function confirmDelete(url, message = 'Are you sure you want to delete this item?') {
  deleteUrl = url;
  document.getElementById('deleteMessage').innerText = message;
  document.getElementById('deleteModal').style.display = 'flex';
}

function closeModal() {
  document.getElementById('deleteModal').style.display = 'none';
  deleteUrl = '';
}

document.addEventListener('DOMContentLoaded', () => {
  const confirmBtn = document.getElementById('confirmDeleteBtn');
  if (confirmBtn) {
    confirmBtn.addEventListener('click', () => {
      if (deleteUrl) window.location.href = deleteUrl;
    });
  }
});
