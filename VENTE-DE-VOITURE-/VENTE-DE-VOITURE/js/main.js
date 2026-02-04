// ===========================
// FONCTIONS UTILITAIRES
// ===========================

// Fonction pour formater les prix
function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 0
    }).format(price);
}

// Fonction pour afficher les alertes
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}

// ===========================
// GESTION DES COMPARAISONS
// ===========================

let comparisonList = JSON.parse(localStorage.getItem('comparison')) || [];

function addToComparison(vehicleId) {
    if (comparisonList.length >= 4) {
        showAlert('Vous ne pouvez comparer que 4 véhicules maximum', 'error');
        return;
    }
    
    if (comparisonList.includes(vehicleId)) {
        showAlert('Ce véhicule est déjà dans votre comparaison', 'info');
        return;
    }
    
    comparisonList.push(vehicleId);
    localStorage.setItem('comparison', JSON.stringify(comparisonList));
    updateComparisonBadge();
    showAlert('Véhicule ajouté à la comparaison', 'success');
}

function removeFromComparison(vehicleId) {
    comparisonList = comparisonList.filter(id => id !== vehicleId);
    localStorage.setItem('comparison', JSON.stringify(comparisonList));
    updateComparisonBadge();
    
    // Recharger la page de comparaison si on y est
    if (window.location.href.includes('comparison.html')) {
        location.reload();
    }
}

function updateComparisonBadge() {
    const badge = document.getElementById('comparison-badge');
    if (badge) {
        badge.textContent = comparisonList.length;
        badge.style.display = comparisonList.length > 0 ? 'inline' : 'none';
    }
}

function clearComparison() {
    comparisonList = [];
    localStorage.removeItem('comparison');
    updateComparisonBadge();
}

// ===========================
// GESTION DES FAVORIS
// ===========================

let favoritesList = JSON.parse(localStorage.getItem('favorites')) || [];

function toggleFavorite(vehicleId) {
    const index = favoritesList.indexOf(vehicleId);
    
    if (index > -1) {
        favoritesList.splice(index, 1);
        showAlert('Retiré des favoris', 'info');
    } else {
        favoritesList.push(vehicleId);
        showAlert('Ajouté aux favoris', 'success');
    }
    
    localStorage.setItem('favorites', JSON.stringify(favoritesList));
    updateFavoriteIcons();
}

function isFavorite(vehicleId) {
    return favoritesList.includes(vehicleId);
}

function updateFavoriteIcons() {
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        const vehicleId = btn.dataset.vehicleId;
        if (isFavorite(vehicleId)) {
            btn.classList.add('active');
            btn.innerHTML = '❤️';
        } else {
            btn.classList.remove('active');
            btn.innerHTML = '🤍';
        }
    });
}

// ===========================
// FILTRES ET RECHERCHE
// ===========================

function applyFilters() {
    const formData = new FormData(document.getElementById('filters-form'));
    const params = new URLSearchParams(formData);
    
    // Rediriger vers la page de catalogue avec les paramètres
    window.location.href = 'Catalogue.html?' + params.toString();
}

function resetFilters() {
    document.getElementById('filters-form').reset();
    window.location.href = 'Catalogue.html';
}

// ===========================
// VALIDATION DES FORMULAIRES
// ===========================

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^[0-9]{10}$/;
    return re.test(phone.replace(/\s/g, ''));
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    let isValid = true;
    
    // Validation email
    const emailInputs = form.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        if (!validateEmail(input.value)) {
            showAlert('Email invalide', 'error');
            input.focus();
            isValid = false;
        }
    });
    
    // Validation téléphone
    const phoneInputs = form.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        if (input.value && !validatePhone(input.value)) {
            showAlert('Numéro de téléphone invalide (10 chiffres)', 'error');
            input.focus();
            isValid = false;
        }
    });
    
    // Validation champs requis
    const requiredInputs = form.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            showAlert('Veuillez remplir tous les champs obligatoires', 'error');
            input.focus();
            isValid = false;
        }
    });
    
    return isValid;
}

// ===========================
// GESTION DES IMAGES
// ===========================

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// ===========================
// CALCULS
// ===========================

function calculateRentalPrice() {
    const startDate = document.getElementById('start-date');
    const endDate = document.getElementById('end-date');
    const dailyRate = document.getElementById('daily-rate');
    const totalPrice = document.getElementById('total-price');
    
    if (startDate && endDate && dailyRate && totalPrice) {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        
        if (days > 0) {
            const total = days * parseFloat(dailyRate.value);
            totalPrice.textContent = formatPrice(total);
            document.getElementById('rental-days').textContent = days;
        }
    }
}

// ===========================
// ANIMATION ET SCROLL
// ===========================

function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Observer pour les animations au scroll
function setupScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, {
        threshold: 0.1
    });
    
    document.querySelectorAll('.vehicle-card').forEach(card => {
        observer.observe(card);
    });
}

// ===========================
// RECHERCHE EN TEMPS RÉEL
// ===========================

function setupLiveSearch() {
    const searchInput = document.getElementById('live-search');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', debounce(function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.vehicle-card');
        
        cards.forEach(card => {
            const brand = card.dataset.brand?.toLowerCase() || '';
            const model = card.dataset.model?.toLowerCase() || '';
            
            if (brand.includes(searchTerm) || model.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }, 300));
}

// Fonction debounce pour limiter les appels
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ===========================
// TRI DES RÉSULTATS
// ===========================

function sortVehicles(criteria) {
    const grid = document.querySelector('.vehicles-grid');
    if (!grid) return;
    
    const cards = Array.from(grid.querySelectorAll('.vehicle-card'));
    
    cards.sort((a, b) => {
        switch(criteria) {
            case 'price-asc':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price-desc':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'km-asc':
                return parseFloat(a.dataset.km) - parseFloat(b.dataset.km);
            case 'km-desc':
                return parseFloat(b.dataset.km) - parseFloat(a.dataset.km);
            case 'year-desc':
                return parseFloat(b.dataset.year) - parseFloat(a.dataset.year);
            default:
                return 0;
        }
    });
    
    // Réorganiser les cartes
    cards.forEach(card => grid.appendChild(card));
}

// ===========================
// CONFIRMATION DE SUPPRESSION
// ===========================

function confirmDelete(message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
    return confirm(message);
}

// ===========================
// GESTION DES ONGLETS
// ===========================

function setupTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.dataset.tab;
            
            // Désactiver tous les onglets
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Activer l'onglet sélectionné
            button.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
}

// ===========================
// INITIALISATION
// ===========================

document.addEventListener('DOMContentLoaded', function() {
    // Mise à jour des badges
    updateComparisonBadge();
    updateFavoriteIcons();
    
    // Setup des fonctionnalités
    setupScrollAnimations();
    setupLiveSearch();
    setupTabs();
    
    // Gestion du formulaire de recherche
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }
    
    // Gestion du tri
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortVehicles(this.value);
        });
    }
    
    // Calcul du prix de location
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', calculateRentalPrice);
        endDateInput.addEventListener('change', calculateRentalPrice);
    }
    
    // Preview d'image
    const imageInput = document.getElementById('vehicle-image');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            previewImage(this, 'image-preview');
        });
    }
});

// ===========================
// FONCTIONS EXPORTÉES
// ===========================

window.vehicleApp = {
    addToComparison,
    removeFromComparison,
    toggleFavorite,
    formatPrice,
    showAlert,
    validateForm,
    confirmDelete,
    sortVehicles,
    calculateRentalPrice
};
