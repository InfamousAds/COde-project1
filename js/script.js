// Main JavaScript file

// DOM elements
const navBtns = document.querySelectorAll('.nav-btn');
const contentSections = document.querySelectorAll('.content-section');
const searchInput = document.getElementById('searchInput');
const levelFilter = document.getElementById('levelFilter');
const fieldFilter = document.getElementById('fieldFilter');
const deadlineFilter = document.getElementById('deadlineFilter');
const sortDeadlineBtn = document.getElementById('sortDeadline');
const sortAmountBtn = document.getElementById('sortAmount');
const resetBtn = document.getElementById('resetBtn');
const scholarshipsList = document.getElementById('scholarshipsList');
const bookmarksList = document.getElementById('bookmarksList');
const modal = document.getElementById('detailModal');
const closeModal = document.querySelector('.close');

// All scholarships data
let allScholarships = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadScholarships();
    loadFilters();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    // Navigation
    navBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            switchSection(this.dataset.section);
        });
    });
    
    // Filters and search
    searchInput.addEventListener('keyup', filterScholarships);
    levelFilter.addEventListener('change', filterScholarships);
    fieldFilter.addEventListener('change', filterScholarships);
    deadlineFilter.addEventListener('change', filterScholarships);
    
    // Sort buttons
    sortDeadlineBtn.addEventListener('click', sortByDeadline);
    sortAmountBtn.addEventListener('click', sortByAmount);
    resetBtn.addEventListener('click', resetFilters);
    
    // Modal
    closeModal.addEventListener('click', closeDetailModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeDetailModal();
    });
}

// Load all scholarships
function loadScholarships() {
    fetch('includes/api.php?action=get_all')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allScholarships = data.data;
                displayScholarships(allScholarships);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Load filter options
function loadFilters() {
    fetch('includes/api.php?action=get_filters')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate levels
                data.levels.forEach(level => {
                    const option = document.createElement('option');
                    option.value = level;
                    option.textContent = level;
                    levelFilter.appendChild(option);
                });
                
                // Populate fields
                data.fields.forEach(field => {
                    const option = document.createElement('option');
                    option.value = field;
                    option.textContent = field;
                    fieldFilter.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

// Switch content section
function switchSection(sectionId) {
    // Update nav buttons
    navBtns.forEach(btn => btn.classList.remove('active'));
    document.querySelector(`[data-section="${sectionId}"]`).classList.add('active');
    
    // Update sections
    contentSections.forEach(section => section.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    
    // Load bookmarks if switching to bookmarks
    if (sectionId === 'bookmarks') {
        loadBookmarks();
    }
}

// Filter scholarships
function filterScholarships() {
    const searchTerm = searchInput.value.toLowerCase();
    const level = levelFilter.value;
    const field = fieldFilter.value;
    const deadline = deadlineFilter.value;
    
    // Build query string
    let params = new URLSearchParams();
    if (level) params.append('level', level);
    if (field) params.append('field', field);
    if (deadline) params.append('deadline', deadline);
    
    let url = 'includes/api.php?action=search';
    if (params.toString()) {
        url += '&' + params.toString();
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let filtered = data.data;
                
                // Client-side text search
                if (searchTerm) {
                    filtered = filtered.filter(s =>
                        s.title.toLowerCase().includes(searchTerm) ||
                        s.provider.toLowerCase().includes(searchTerm)
                    );
                }
                
                displayScholarships(filtered);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Sort by deadline
function sortByDeadline() {
    fetch('includes/api.php?action=sort_deadline')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allScholarships = data.data;
                displayScholarships(allScholarships);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Sort by amount
function sortByAmount() {
    fetch('includes/api.php?action=sort_amount')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allScholarships = data.data;
                displayScholarships(allScholarships);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Reset filters
function resetFilters() {
    searchInput.value = '';
    levelFilter.value = '';
    fieldFilter.value = '';
    deadlineFilter.value = '';
    displayScholarships(allScholarships);
}

// Display scholarships
function displayScholarships(scholarships) {
    if (scholarships.length === 0) {
        scholarshipsList.innerHTML = '<div class="empty-state"><p>No scholarships found</p></div>';
        return;
    }
    
    scholarshipsList.innerHTML = '';
    
    scholarships.forEach(scholarship => {
        const card = createScholarshipCard(scholarship);
        scholarshipsList.appendChild(card);
    });
}

// Create scholarship card
function createScholarshipCard(scholarship) {
    const card = document.createElement('div');
    card.className = 'scholarship-card';
    
    const deadline = new Date(scholarship.deadline);
    const formattedDeadline = deadline.toLocaleDateString();
    
    card.innerHTML = `
        <h3>${escapeHtml(scholarship.title)}</h3>
        <div class="scholarship-info">
            <p><span>Provider:</span> ${escapeHtml(scholarship.provider)}</p>
            <p><span>Level:</span> ${escapeHtml(scholarship.education_level)}</p>
            <p><span>Field:</span> ${escapeHtml(scholarship.field)}</p>
            <p><span>Type:</span> ${escapeHtml(scholarship.scholarship_type)}</p>
        </div>
        <div class="scholarship-amount">₱${parseFloat(scholarship.amount).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
        <div class="scholarship-deadline">Deadline: ${formattedDeadline}</div>
        <div class="scholarship-actions">
            <button class="btn" onclick="showDetails(${scholarship.id})">Details</button>
            <button class="btn btn-bookmark" onclick="toggleBookmark(${scholarship.id}, this)">Bookmark</button>
        </div>
    `;
    
    // Update bookmark button state
    checkIfBookmarked(scholarship.id, card.querySelector('.btn-bookmark'));
    
    return card;
}

// Show scholarship details modal
function showDetails(scholarshipId) {
    const scholarship = allScholarships.find(s => s.id == scholarshipId);
    
    if (!scholarship) return;
    
    const deadline = new Date(scholarship.deadline);
    const formattedDeadline = deadline.toLocaleDateString();
    
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <h2>${escapeHtml(scholarship.title)}</h2>
        <div class="scholarship-info">
            <p><span>Provider:</span> ${escapeHtml(scholarship.provider)}</p>
            <p><span>Education Level:</span> ${escapeHtml(scholarship.education_level)}</p>
            <p><span>Field:</span> ${escapeHtml(scholarship.field)}</p>
            <p><span>Type:</span> ${escapeHtml(scholarship.scholarship_type)}</p>
            <p><span>Amount:</span> ₱${parseFloat(scholarship.amount).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
            <p><span>Deadline:</span> ${formattedDeadline}</p>
        </div>
        <div class="scholarship-info">
            <p><span>Eligibility:</span></p>
            <p>${escapeHtml(scholarship.eligibility)}</p>
        </div>
        <div class="scholarship-actions">
            <a href="${escapeHtml(scholarship.application_link)}" target="_blank" class="btn">Apply Now</a>
            <button class="btn btn-bookmark" onclick="toggleBookmark(${scholarship.id}, this)">Bookmark</button>
        </div>
    `;
    
    // Update bookmark button state
    checkIfBookmarked(scholarshipId, modalBody.querySelector('.btn-bookmark'));
    
    modal.classList.add('show');
}

// Close modal
function closeDetailModal() {
    modal.classList.remove('show');
}

// Toggle bookmark
function toggleBookmark(scholarshipId, button) {
    // Check if already bookmarked
    if (button.classList.contains('bookmarked')) {
        removeBookmark(scholarshipId, button);
    } else {
        addBookmark(scholarshipId, button);
    }
}

// Add bookmark
function addBookmark(scholarshipId, button) {
    const formData = new FormData();
    formData.append('scholarship_id', scholarshipId);
    
    fetch('includes/api.php?action=add_bookmark', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.classList.add('bookmarked');
            button.textContent = 'Bookmarked';
        } else {
            alert(data.message || 'Error adding bookmark');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Remove bookmark
function removeBookmark(scholarshipId, button) {
    const formData = new FormData();
    formData.append('scholarship_id', scholarshipId);
    
    fetch('includes/api.php?action=remove_bookmark', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.classList.remove('bookmarked');
            button.textContent = 'Bookmark';
            // Refresh bookmarks if on bookmarks page
            if (document.getElementById('bookmarks').classList.contains('active')) {
                loadBookmarks();
            }
        } else {
            alert(data.message || 'Error removing bookmark');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Check if bookmarked
function checkIfBookmarked(scholarshipId, button) {
    fetch(`includes/api.php?action=is_bookmarked&scholarship_id=${scholarshipId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.is_bookmarked) {
                button.classList.add('bookmarked');
                button.textContent = 'Bookmarked';
            }
        })
        .catch(error => console.error('Error:', error));
}

// Load bookmarks
function loadBookmarks() {
    fetch('includes/api.php?action=get_bookmarks')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data.length === 0) {
                    bookmarksList.innerHTML = '<div class="empty-state"><p>No bookmarked scholarships yet</p></div>';
                } else {
                    bookmarksList.innerHTML = '';
                    data.data.forEach(scholarship => {
                        const card = createScholarshipCard(scholarship);
                        bookmarksList.appendChild(card);
                    });
                }
            } else {
                bookmarksList.innerHTML = '<div class="empty-state"><p>Please log in to view bookmarks</p></div>';
            }
        })
        .catch(error => console.error('Error:', error));
}

// Escape HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
