/**
 * Attendance System - JavaScript Helper Functions
 * 
 * File ini berisi fungsi-fungsi JavaScript untuk handle attendance system
 * Gunakan di blade template Anda untuk check-in, check-out, dan fitur lainnya
 */

// ========================================
// 1. CHECK IN FUNCTION
// ========================================
async function checkIn() {
    try {
        // Get location (bisa dari GPS atau input manual)
        const location = await getUserLocation();
        
        const response = await fetch('/attendance/check-in', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                location: location
            })
        });

        const data = await response.json();

        if (data.success) {
            showNotification('success', data.message);
            // Refresh status atau redirect
            window.location.reload();
        } else {
            showNotification('error', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan saat check-in');
    }
}

// ========================================
// 2. CHECK OUT FUNCTION
// ========================================
async function checkOut(notes = '') {
    try {
        const location = await getUserLocation();
        
        const response = await fetch('/attendance/check-out', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                location: location,
                notes: notes
            })
        });

        const data = await response.json();

        if (data.success) {
            showNotification('success', data.message);
            window.location.reload();
        } else {
            showNotification('error', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan saat check-out');
    }
}

// ========================================
// 3. GET TODAY STATUS
// ========================================
async function getTodayStatus() {
    try {
        const response = await fetch('/attendance/today-status', {
            headers: {
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            return data.data;
        }
    } catch (error) {
        console.error('Error:', error);
        return null;
    }
}

// ========================================
// 4. SUBMIT LEAVE/SICK
// ========================================
async function submitLeave(date, type, notes) {
    try {
        const response = await fetch('/attendance/submit-leave', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                date: date,
                type: type, // 'sick' atau 'leave'
                notes: notes
            })
        });

        const data = await response.json();

        if (data.success) {
            showNotification('success', data.message);
            return true;
        } else {
            showNotification('error', data.message);
            return false;
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan saat submit izin');
        return false;
    }
}

// ========================================
// 5. GET STATISTICS
// ========================================
async function getStatistics(month = null, year = null) {
    try {
        const currentDate = new Date();
        const queryMonth = month || (currentDate.getMonth() + 1);
        const queryYear = year || currentDate.getFullYear();

        const response = await fetch(`/attendance/statistics?month=${queryMonth}&year=${queryYear}`, {
            headers: {
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            return data.data;
        }
    } catch (error) {
        console.error('Error:', error);
        return null;
    }
}

// ========================================
// HELPER FUNCTIONS
// ========================================

/**
 * Get user location menggunakan Geolocation API
 */
function getUserLocation() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            // Jika browser tidak support geolocation, return default location
            resolve('Location not available');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                resolve(`Lat: ${lat}, Lng: ${lng}`);
            },
            (error) => {
                console.warn('Geolocation error:', error);
                // Fallback ke input manual atau default
                resolve('Location not available');
            }
        );
    });
}

/**
 * Show notification to user
 */
function showNotification(type, message) {
    // Contoh sederhana - sesuaikan dengan UI framework yang Anda gunakan
    alert(message);
    
    // Atau gunakan library seperti SweetAlert2, Toastr, dll
    // Swal.fire({
    //     icon: type,
    //     title: type === 'success' ? 'Berhasil!' : 'Gagal!',
    //     text: message
    // });
}

/**
 * Format time untuk display
 */
function formatTime(timeString) {
    if (!timeString) return '-';
    const [hours, minutes] = timeString.split(':');
    return `${hours}:${minutes}`;
}

/**
 * Calculate work hours
 */
function calculateWorkHours(checkIn, checkOut) {
    if (!checkIn || !checkOut) return 0;
    
    const checkInTime = new Date(`2000-01-01 ${checkIn}`);
    const checkOutTime = new Date(`2000-01-01 ${checkOut}`);
    
    const diffMs = checkOutTime - checkInTime;
    const diffHours = diffMs / (1000 * 60 * 60);
    
    return diffHours.toFixed(2);
}

/**
 * Update UI based on attendance status
 */
async function updateAttendanceUI() {
    const status = await getTodayStatus();
    
    if (!status) return;

    const checkInBtn = document.getElementById('check-in-btn');
    const checkOutBtn = document.getElementById('check-out-btn');
    const statusDisplay = document.getElementById('status-display');

    if (status.has_checked_in) {
        if (checkInBtn) checkInBtn.disabled = true;
        if (statusDisplay) {
            statusDisplay.innerHTML = `
                <p>Check In: ${formatTime(status.attendance.check_in)}</p>
                <p>Status: ${status.attendance.status}</p>
            `;
        }
    }

    if (status.has_checked_out) {
        if (checkOutBtn) checkOutBtn.disabled = true;
        if (statusDisplay) {
            statusDisplay.innerHTML += `
                <p>Check Out: ${formatTime(status.attendance.check_out)}</p>
                <p>Jam Kerja: ${status.attendance.work_hours} jam</p>
            `;
        }
    }
}

/**
 * Display statistics on dashboard
 */
async function displayStatistics(containerId, month = null, year = null) {
    const stats = await getStatistics(month, year);
    
    if (!stats) return;

    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = `
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Hadir</h3>
                <p class="stat-number">${stats.total_present}</p>
            </div>
            <div class="stat-card">
                <h3>Terlambat</h3>
                <p class="stat-number">${stats.total_late}</p>
            </div>
            <div class="stat-card">
                <h3>Absen</h3>
                <p class="stat-number">${stats.total_absent}</p>
            </div>
            <div class="stat-card">
                <h3>Sakit</h3>
                <p class="stat-number">${stats.total_sick}</p>
            </div>
            <div class="stat-card">
                <h3>Izin</h3>
                <p class="stat-number">${stats.total_leave}</p>
            </div>
            <div class="stat-card">
                <h3>Total Jam Kerja</h3>
                <p class="stat-number">${stats.total_work_hours.toFixed(2)} jam</p>
            </div>
        </div>
    `;
}

// ========================================
// EXAMPLE USAGE IN BLADE
// ========================================

/*
<!-- Di blade template (misalnya clock-in.blade.php) -->

<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Check In</title>
</head>
<body>
    <div id="status-display"></div>
    
    <button id="check-in-btn" onclick="checkIn()">Check In</button>
    <button id="check-out-btn" onclick="checkOut()">Check Out</button>

    <div id="statistics-container"></div>

    <script src="{{ asset('js/attendance-helper.js') }}"></script>
    <script>
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateAttendanceUI();
            displayStatistics('statistics-container');
        });
    </script>
</body>
</html>
*/

/*
<!-- Contoh form submit leave -->

<form id="leave-form" onsubmit="handleLeaveSubmit(event)">
    <input type="date" id="leave-date" required>
    <select id="leave-type" required>
        <option value="sick">Sakit</option>
        <option value="leave">Izin</option>
    </select>
    <textarea id="leave-notes" required></textarea>
    <button type="submit">Submit</button>
</form>

<script>
async function handleLeaveSubmit(event) {
    event.preventDefault();
    
    const date = document.getElementById('leave-date').value;
    const type = document.getElementById('leave-type').value;
    const notes = document.getElementById('leave-notes').value;
    
    const success = await submitLeave(date, type, notes);
    
    if (success) {
        document.getElementById('leave-form').reset();
    }
}
</script>
*/
