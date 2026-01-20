/**
 * Optimized Attendance System JavaScript
 * Handles fast clock-in/clock-out with minimal network requests
 */

class AttendanceManager {
    constructor() {
        this.cacheKey = 'attendance_cache';
        this.cacheExpiry = 60000; // 1 minute cache
        this.debounceTimer = null;
    }

    /**
     * Get today's attendance status with caching
     */
    async getTodayStatus(forceRefresh = false) {
        const cached = this.getFromCache();
        
        if (cached && !forceRefresh) {
            return cached;
        }

        try {
            const response = await fetch('/attendance/today-status', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!response.ok) throw new Error('Failed to fetch status');
            
            const data = await response.json();
            
            if (data.success) {
                this.setCache(data.data);
                return data.data;
            }
        } catch (error) {
            console.error('Error fetching today status:', error);
            return cached || { has_checked_in: false, has_checked_out: false };
        }
    }

    /**
     * Perform check-in with optimized request
     */
    async checkIn(location, note) {
        return this.performAttendanceAction('/check-in', {
            location: JSON.stringify(location),
            note: note
        });
    }

    /**
     * Perform check-out with optimized request
     */
    async checkOut(location, notes) {
        return this.performAttendanceAction('/check-out', {
            location: JSON.stringify(location),
            notes: notes
        });
    }

    /**
     * Generic attendance action handler
     */
    async performAttendanceAction(endpoint, data) {
        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                // Clear cache on successful action
                this.clearCache();
                // Force refresh after action
                setTimeout(() => this.getTodayStatus(true), 1000);
            }

            return result;
        } catch (error) {
            console.error('Attendance action error:', error);
            return {
                success: false,
                message: 'Terjadi kesalahan. Silakan coba lagi.'
            };
        }
    }

    /**
     * Cache management
     */
    setCache(data) {
        localStorage.setItem(this.cacheKey, JSON.stringify({
            data: data,
            timestamp: Date.now()
        }));
    }

    getFromCache() {
        const cached = localStorage.getItem(this.cacheKey);
        if (!cached) return null;

        try {
            const { data, timestamp } = JSON.parse(cached);
            if (Date.now() - timestamp < this.cacheExpiry) {
                return data;
            }
        } catch (e) {
            console.error('Cache parse error:', e);
        }

        return null;
    }

    clearCache() {
        localStorage.removeItem(this.cacheKey);
    }
}

// Debounced form submission handler
function debounceSubmit(formElement, callback) {
    formElement.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable button to prevent double submission
        const submitBtn = formElement.querySelector('[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        }

        // Execute callback with debounce
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            callback.call(this, e);
        }, 300);
    });
}

// Lazy load images when they come into view
function initLazyImages() {
    if ('IntersectionObserver' in window) {
        const images = document.querySelectorAll('img[data-lazy]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.lazy;
                    img.removeAttribute('data-lazy');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
}

// Request debouncing for network calls
class RequestDebouncer {
    constructor(delay = 500) {
        this.delay = delay;
        this.pending = new Map();
    }

    execute(key, fn) {
        if (this.pending.has(key)) {
            clearTimeout(this.pending.get(key));
        }

        return new Promise((resolve, reject) => {
            const timer = setTimeout(async () => {
                try {
                    const result = await fn();
                    this.pending.delete(key);
                    resolve(result);
                } catch (error) {
                    this.pending.delete(key);
                    reject(error);
                }
            }, this.delay);

            this.pending.set(key, timer);
        });
    }
}

// Initialize global instances
const attendanceManager = new AttendanceManager();
const requestDebouncer = new RequestDebouncer();

// Performance monitoring
class PerformanceMonitor {
    static logMetric(name, value) {
        if (window.performance && window.performance.mark) {
            performance.measure(name);
            console.debug(`Metric ${name}:`, value);
        }
    }

    static trackPageLoadTime() {
        if (window.performance && window.performance.timing) {
            window.addEventListener('load', () => {
                const pageLoadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
                this.logMetric('page-load-time', pageLoadTime);
            });
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize lazy loading
    initLazyImages();
    
    // Track performance
    PerformanceMonitor.trackPageLoadTime();
    
    // Prefetch critical resources
    prefetchCriticalResources();
});

/**
 * Prefetch critical resources for faster navigation
 */
function prefetchCriticalResources() {
    const criticalLinks = [
        '/dashboard',
        '/attendance/today-status',
        '/leave/index'
    ];

    criticalLinks.forEach(href => {
        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = href;
        document.head.appendChild(link);
    });
}

/**
 * Utility: Format time strings for display
 */
function formatTime(timeString) {
    if (!timeString) return '--:--';
    const [hours, minutes] = timeString.split(':');
    return `${hours}:${minutes}`;
}

/**
 * Utility: Show loading state on element
 */
function showLoadingState(element) {
    if (element) {
        element.disabled = true;
        element.style.opacity = '0.6';
        element.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
    }
}

/**
 * Utility: Hide loading state on element
 */
function hideLoadingState(element, originalText) {
    if (element) {
        element.disabled = false;
        element.style.opacity = '1';
        element.innerHTML = originalText;
    }
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { AttendanceManager, RequestDebouncer, PerformanceMonitor };
}
