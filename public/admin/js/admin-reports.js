/**
 * DriveEase — Admin Analytics & Reports Logic
 * admin-reports.js
 */

$(document).ready(function() {
  Storage.seed();
  initReportCharts();

  $('#exportCsvBtn').on('click', function() {
    exportBookingsCSV();
  });
});

function initReportCharts() {
  const ctxBookings = document.getElementById('bookingsTrendChart');
  if (ctxBookings) {
    new Chart(ctxBookings.getContext('2d'), {
      type: 'bar',
      data: {
        labels: MONTHLY_BOOKINGS.labels,
        datasets: [{
          label: 'Total Bookings',
          data: MONTHLY_BOOKINGS.data,
          backgroundColor: '#3b82f6',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
      }
    });
  }

  const ctxUtil = document.getElementById('catUtilChart');
  if (ctxUtil) {
    const cats = Storage.getCategories();
    new Chart(ctxUtil.getContext('2d'), {
      type: 'bar',
      data: {
        labels: cats.map(c => c.name),
        datasets: [{
          label: 'Vehicles Count',
          data: cats.map(c => c.cars),
          backgroundColor: '#10b981',
          borderRadius: 6
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
      }
    });
  }
}

function exportBookingsCSV() {
  const bookings = Storage.getBookings();
  let csv = 'BookingID,Customer,Car,PickupDate,ReturnDate,TotalAmount,Status\n';
  
  bookings.forEach(b => {
    csv += `"${b.id}","${b.customerName}","${b.carName}","${b.pickupDate}","${b.returnDate}",${b.total},"${b.status}"\n`;
  });

  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.setAttribute('href', url);
  link.setAttribute('download', `DriveEase_Report_${DateHelper.todayStr()}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  Toast.success('Export Complete', 'Bookings CSV file downloaded successfully.');
}
