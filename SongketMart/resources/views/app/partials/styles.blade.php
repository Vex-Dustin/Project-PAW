<style>
    /* ==========================================================================
       1. ROOT VARIABLES & BASE STYLES
       ========================================================================== */
    :root {
        --primary-maroon: #800000;
        --secondary-cream: #FDFBF7;
        --accent-gold: #D4AF37;
        --text-dark: #2D2D2D;
        --text-muted: #757575;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--secondary-cream);
        color: var(--text-dark);
        margin-bottom: 70px;
    }

    @media (min-width: 768px) {
        body {
            margin-bottom: 0;
            padding-top: 70px;
        }
    }

    /* ==========================================================================
       2. NAVIGATION LAYOUTS (DESKTOP & MOBILE)
       ========================================================================== */
    .navbar-desktop {
        background-color: white;
        border-bottom: 1px solid #eee;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    .bottom-nav {
        background-color: white;
        border-top: 1px solid #eee;
        position: fixed;
        bottom: 0;
        width: 100%;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px 0;
        z-index: 1030;
        height: 65px;
    }

    @media (min-width: 768px) {
        .bottom-nav {
            display: none !important;
        }
    }

    .nav-item-mobile {
        flex: 1;
        text-align: center;
        color: #6c757d;
        text-decoration: none;
        font-size: 0.65rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.2s ease;
    }

    .nav-item-mobile.active {
        color: var(--primary-maroon) !important;
        font-weight: bold;
    }

    .nav-item-mobile i {
        font-size: 1.3rem;
        margin-bottom: 1px;
        display: block;
    }

    /* ==========================================================================
       3. BUTTONS & GENERAL UTILITIES
       ========================================================================== */
    .btn-maroon {
        background-color: var(--primary-maroon);
        color: white;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-maroon:hover {
        background-color: #600000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(128, 0, 0, 0.2);
    }

    .btn-outline-maroon {
        color: var(--primary-maroon);
        border: 1px solid var(--primary-maroon);
    }

    .btn-outline-maroon:hover {
        background-color: var(--primary-maroon);
        color: white !important;
    }

    .text-maroon {
        color: #800000;
    }

    .bg-maroon {
        background-color: #800000;
    }

    .active-maroon {
        background-color: var(--primary-maroon) !important;
        color: white !important;
        border-color: var(--primary-maroon) !important;
    }

    .active-maroon i {
        color: white !important;
    }

    .pill {
        border-radius: 50px;
        font-size: 0.85rem;
        transition: all 0.3s;
    }

    .italic {
        font-style: italic;
    }

    .overflow-auto::-webkit-scrollbar {
        display: none;
    }

    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ==========================================================================
       4. CARDS & INTERACTIVE HOVER EFFECTS
       ========================================================================== */
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-img-top {
        transition: transform 0.3s ease;
    }

    .card:hover .card-img-top {
        transform: scale(1.05);
    }

    .shadow-hover {
        transition: all 0.3s ease;
    }

    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    }

    /* ==========================================================================
       5. FORMS & HIGHLIGHT BOXES
       ========================================================================== */
    .form-control:focus {
        border-color: var(--primary-maroon);
        box-shadow: 0 0 0 0.25rem rgba(128, 0, 0, 0.1);
    }

    .shop-box-highlight {
        background-color: rgba(128, 0, 0, 0.05);
        border: 1px dashed var(--primary-maroon);
    }

    /* ==========================================================================
       6. TABLES & STATUS BADGES (SUBTLE STYLE)
       ========================================================================== */
    .table-responsive {
        border: none;
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .bg-success-subtle {
        background-color: #e8f5e9 !important;
        color: #2e7d32 !important;
    }

    .bg-warning-subtle {
        background-color: #fff3e0 !important;
        color: #ef6c00 !important;
    }

    .bg-danger-subtle {
        background-color: #ffebee !important;
        color: #c62828 !important;
    }

    .bg-secondary-subtle {
        background-color: #f5f5f5 !important;
        color: #616161 !important;
    }

    /* ==========================================================================
       7. BOOTSTRAP MODAL FIXES & SELLER SCROLL WRAPPER
       ========================================================================== */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }

    .modal {
        background: rgba(0, 0, 0, 0.4);
    }

    .modal-backdrop {
        display: none !important;
    }

    @media (max-width: 768px) {
        .seller-main-wrapper {
            overflow: visible !important;
            padding-bottom: 20px !important;
        }

        body {
            padding-bottom: 0 !important;
        }
    }

    .hover-maroon {
        transition: color 0.2s ease-in-out;
    }
    
    .hover-maroon:hover {
        color: var(--primary-maroon) !important;
        text-decoration: underline !important;
    }
</style>
